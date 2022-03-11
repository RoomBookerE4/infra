<?php

namespace App\Controller;

use App\Domain\Auth\Entity\User;
use App\Domain\Auth\UserService;
use App\Form\BookingForm;
use App\Domain\Booking\BookingFormDTO;
use App\Domain\Booking\BookingService;
use App\Domain\Booking\Entity\Booking;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Booking\Exception\CannotBookException;
use App\Domain\Booking\Exception\CannotCancelBookingException;
use App\Domain\Booking\InvitationStatus;
use LogicException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BookingController extends AbstractController
{
    public function __construct(
        private BookingService $bookingService
    )
    {
        
    }

    #[Route('/booking', name: 'booking')]
    public function booking(Request $request): Response
    {
        $booking = new BookingFormDTO();
        $form = $this->createForm(BookingForm::class, $booking, [
            'action' => $this->generateUrl('booking'),
            'openedHours' => $this->getEstblishmentOpeningRange(),
            'establishment' => $this->getUserOrThrow()->getEstablishment(),
            'currentUser' => $this->getUserEntity()
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $booking = $form->getData();
            
            try{
                $this->bookingService->book($booking, $this->getUser());
                $this->addFlash('success', 'Reservation effectuée !');
            }
            catch(CannotBookException $e){
                $this->addFlash('danger', $e->getMessage());
            }

            return $this->redirectToRoute('home');
        }

        return $this->render('booking/_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route("/booking/{id}", name: "booking_view")]
    public function view(Booking $booking): Response
    {
        // TODO : Check if user is granted to view the booking.
        
        return $this->render('booking/view.html.twig', [
            'booking' => $booking
        ]);
    }

    #[Route('/invitation/{id}/{userId}/{state}', name: "invitation_answer")]
    #[Entity("user", expr: "repository.find(userId)", class: User::class)]
    #[IsGranted("ROLE_USER")]
    public function invitationAnswer(Booking $booking, User $user, string $state, Request $request): Response
    {
        if($user != $this->getUserEntity()){
            throw new AccessDeniedException("Impossible d'accepter ou refuser une invitation qui ne vous est pas destinée.");
        }

        try{
            match($state){
                InvitationStatus::ACCEPTED  => $this->bookingService->accept($booking, $user) && $this->addFlash('success', 'Invitation acceptée'),
                InvitationStatus::REJECTED  => $this->bookingService->reject($booking, $user) && $this->addFlash('danger', 'Invitation refusée'),
                InvitationStatus::PENDING   => $this->bookingService->pending($booking, $user) && $this->addFlash('notice', 'Invitation en attente')
            };
        }
        catch(\UnhandledMatchError $e){
            throw new LogicException("L'état de la réservation souhaité n'est pas connu.", 500, $e);
        }

        return $this->redirectToRoute("home");
    }

    #[Route("/cancel/{id}", name: "booking_cancel")]
    #[IsGranted('ROLE_USER')]
    public function cancel(Booking $booking): Response
    {
        try {
            $this->bookingService->cancel($booking, $this->getUserEntity());
            $this->addFlash('success', "La réservation a bien été annulée. Un mail d'information à été envoyé à tous les participants.");
        } catch (CannotCancelBookingException $e) {
            $this->addFlash('danger', $e->getMessage());
        }
        
        return $this->redirectToRoute("home");
    }

    /**
     * Establishment opening range.
     * 
     * An estblishment is defined by its timeOpen and timeClose, within we can book a room. Outside this time window,
     * it is impossible to book a room.
     * This method returns the opened hours as a range of opened hours.
     * 
     *
     * @return array [9, 10, 11, ..., 20] for example.
     */
    private function getEstblishmentOpeningRange(): array
    {
        return range(
            (int) $this->getUserOrThrow()->getEstablishment()->getTimeopen()->format('H'),
            (int) $this->getUserOrThrow()->getEstablishment()->getTimeclose()->format('H') - 1, // Minus 1 to be sure we can't book a room at 18:55 if the institution closes at 18:00.
            1
        );
    }
}
