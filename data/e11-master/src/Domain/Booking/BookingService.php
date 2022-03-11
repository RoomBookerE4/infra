<?php

namespace App\Domain\Booking;

use DateTime;

use DateTimeInterface;
use App\Domain\Auth\Entity\User;
use App\Domain\Booking\Entity\Room;
use App\Domain\Shared\MailerService;
use App\Domain\Booking\Entity\Booking;
use App\Domain\Booking\InvitationStatus;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Booking\Entity\Participant;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Booking\Repository\BookingRepository;
use App\Domain\Booking\Exception\CannotBookException;
use App\Domain\Booking\Exception\CannotCancelBookingException;

/**
 * Handles mutations for Booking.
 */
class BookingService{

    public function __construct(
        private EntityManagerInterface $em,
        private BookingRepository $bookingRepository,
        private MailerService $mailerService,
        private RouterInterface $router,
        private Security $security
    )
    {
        
    }

    /**
     * Book a Room, with according datas.
     *
     * @param BookingFormDTO $dto
     * @return Booking
     */
    public function book(BookingFormDTO $dto, User $user): Booking
    {
        $booking = new Booking();

        // Set the organizer of the meeting.
        $organizer = (new Participant())->setUser($user)->setIsInvitation(false)->setInvitationStatus(InvitationStatus::ACCEPTED);

        // Get date params as int. No other way found to do this easily, according to our input.
        $year = (int) $dto->getDate()->format('Y');
        $month = (int) $dto->getDate()->format('m');
        $day = (int) $dto->getDate()->format('d');
        
        $startDateTime = DateTime::createFromInterface($dto->getTimeStart())->setDate($year, $month, $day);
        $endDateTime = DateTime::createFromInterface($dto->getTimeEnd())->setDate($year, $month, $day);
        // Be aware to actually compute start and THEN end because we need to have a positive interval.
        $bookingTime = $startDateTime->diff($endDateTime);
        $bookingTimeMax = $startDateTime->diff($dto->getRoom()->getMaxTime());

        // Assert that time end is AFTER the time start.
        if($endDateTime < $startDateTime){
            throw new CannotBookException("Impossible d'avoir une heure de fin inférieure à l'heure de début.", 500, null);
        }

        // We need to check if the room is booked at that time.
        if($this->isRoomBooked($dto->getRoom(), $startDateTime, $endDateTime)){
            throw new CannotBookException("La salle ".$dto->getRoom()." est déjà réservée à ce moment.");
        }
        
        // We also need to check if the booking time is not > room maxTime.
        if($startDateTime->add($bookingTime) > $startDateTime->add($bookingTimeMax)){
            throw new CannotBookException(sprintf("Cette salle ne peut pas être réservée plus de %s heures", $dto->getRoom()->getMaxTime()->format("H:m:s")));
        }

        $booking->setRoom($dto->getRoom());
        $booking->setTimeStart($startDateTime);
        $booking->setTimeEnd($endDateTime);
        // We add the participants. First, the organizer. Then, the others.
        $booking->addParticipant($organizer);

        // We persist the booking in the database because we need to have its ID to generate corresponding URL.
        try{
            $this->em->persist($booking);
            $this->em->flush();
        }
        catch(\Exception $e){
            throw new CannotBookException("Impossible d'enregistrer la réservation.");
        }

        /** @var \App\Domain\Auth\Entity\User $participantUser */
        foreach ($dto->getParticipants() as $participantUser) {
            // Check if the current organizer is mentionned as participant and skip him/her.
            if($participantUser === $user){
                continue; // onto next participant.
            }

            $booking->addParticipant(
                (new Participant())
                    ->setUser($participantUser)
                    ->setIsInvitation(true)
                    ->setInvitationStatus(InvitationStatus::PENDING)
            );

            try{
                $toMail = $participantUser->getEmail();
                $toString = $participantUser->getUserIdentifier();
                $subject = "Invitation à une réunion";
                $text = "Invitation à une réunion.";

                $acceptUrl = $this->router->generate('invitation_answer', ['id' => $booking->getId(), 'userId' => $participantUser->getId(), 'state' => InvitationStatus::ACCEPTED], RouterInterface::ABSOLUTE_URL);
                $rejectUrl = $this->router->generate('invitation_answer', ['id' => $booking->getId(), 'userId' => $participantUser->getId(), 'state' => InvitationStatus::REJECTED], RouterInterface::ABSOLUTE_URL);
                $html = $this->twig->render('booking/_invitation.html.twig', [
                    'firstName' => $participantUser->getName(),
                    'lastName' => $participantUser->getSurname(),
                    'organizer' => $organizer,
                    'booking' => $booking,
                    'acceptUrl' => $acceptUrl,
                    'rejectUrl' => $rejectUrl
                ]);

                $this->mailerService->sendEmail(
                    $toMail,
                    $toString,
                    $subject,
                    $text,
                    'booking/_invitation.html.twig',
                    [
                        'firstName' => $participantUser->getName(),
                        'lastName' => $participantUser->getSurname(),
                        'organizer' => $organizer,
                        'booking' => $booking,
                        'acceptUrl' => $acceptUrl,
                        'rejectUrl' => $rejectUrl
                    ]
                );
            }
            catch(\Exception $e){
                throw new CannotBookException("Envoi de mail impossible.", 1, $e);
            }
        }

        try{
            $this->em->flush();
        }
        catch(\Exception $e){
            throw new CannotBookException("Impossible d'enregistrer la réservation.");
        }
        
        return $booking;
    }

    /**
     * Find meetings. Aggregate function useful for internal use only.
     *
     * @param User|null $user
     * @param Room|null $room
     * @param DateTimeInterface|null $start
     * @param DateTimeInterface|null $end
     * @return array
     */
    private function findMeetings(?User $user = null, ?Room $room = null, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): array
    {
        return $this->bookingRepository->findMeetings($user, $room, $start, $end);
    }

    /**
     * Find only upcoming meetings, for specified user.
     *
     * @param User $user
     * @return void
     */
    public function upcomingMeetings(User $user): array
    {
        return $this->findMeetings($user, null, new \DateTime());
    }

    /**
     * Fin ALL ended meetings until now for a specified - or not - user.
     *
     * @param User|null $user
     * @return array
     */
    public function endedMeetings(?User $user): array
    {
        return $this->findMeetings($user, null, null, new \DateTime());
    }

    /**
     * Checks wether a room is booked or not at a given time.
     *
     * @param Room $room
     * @param DateTime $start
     * @param DateTime $end
     * @return boolean
     */
    public function isRoomBooked(Room $room, DateTime $start, DateTime $end): bool
    {
        $meetings = $this->bookingRepository->findMeetings(null, $room, $start, $end);

        return count($meetings) > 0;
    }

    /**
     * Accept a booking with a given participant.
     *
     * @param Booking $booking
     * @param User $user
     * @return void
     */
    public function accept(Booking $booking, User $user): void
    {
        $this->filterParticipantWithUser($booking, $user)->setInvitationStatus(InvitationStatus::ACCEPTED);
        $this->mailerService->sendEmail(
            $user->getEmail(),
            $user->getUserIdentifier(),
            sprintf("%s a accepté votre invitation", $user),
            sprintf("%s a accepté votre invitation pour la réunion du %s", $user, $booking->getTimeStart()->format('d/m/Y - H:m:s'))
        );

        $this->em->flush();
    }

    /**
     * Reject a booking with a given participant.
     *
     * @param Booking $booking
     * @param User $user
     * @return void
     */
    public function reject(Booking $booking, User $user): void
    {
        $this->filterParticipantWithUser($booking, $user)->setInvitationStatus(InvitationStatus::REJECTED);
        
        $this->mailerService->sendEmail(
            $user->getEmail(),
            $user->getUserIdentifier(),
            sprintf("%s a refusé votre invitation", $user),
            sprintf("%s a refusé votre invitation pour la réunion du %s", $user, $booking->getTimeStart()->format('d/m/Y - H:m:s'))
        );

        $this->em->flush();
    }

    /**
     * Put a booking as pending with a given participant.
     *
     * @param Booking $booking
     * @param User $user
     * @return void
     */
    public function pending(Booking $booking, User $user): void
    {
        $this->filterParticipantWithUser($booking, $user)->setInvitationStatus(InvitationStatus::PENDING);
        
        $this->mailerService->sendEmail(
            $user->getEmail(),
            $user->getUserIdentifier(),
            sprintf("%s a mis votre invitation en attente", $user),
            sprintf("%s a mis votre invitation en attente pour la réunion du %s", $user, $booking->getTimeStart()->format('d/m/Y - H:m:s'))
        );

        $this->em->flush();
    }

    /**
     * Cancels a meeting.
     * When a meeting is canceled we want to notify each participant with an email stating the meeting has been canceled.
     *
     * @param Booking $booking
     * @return void
     */
    public function cancel(Booking $booking, User $user): void
    {
        if(!$this->isOrganizer($booking, $user) || $this->security->isGranted('ROLE_MANAGEMENT')){
            throw new CannotCancelBookingException("Un participant non organisateur ne peut pas annuler une réunion.");
        }

        /** @var Participant $participant */
        foreach ($booking->getParticipants() as $participant) {
            /**
             * We want everyone to be informed, even the organizer:
             * Meeting could have been canceled by an admin zB.
             */
            $user = $participant->getUser();

            try{
                $toMail = $user->getEmail();
                $toString = $user->getUserIdentifier();
                $subject = "Réunion annulée.";
                $text = "Réunion annulée.";

                $this->mailerService->sendEmail(
                    $toMail,
                    $toString,
                    $subject,
                    $text, 
                    'booking/_cancel.html.twig', 
                    [
                        'firstName' => $user->getName(),
                        'lastName' => $user->getSurname(),
                        'booking' => $booking,
                    ]
                );
            }
            catch(\Exception $e){
                throw new CannotCancelBookingException("Envoi de mail impossible. La réunion n'a pas été annulée.", 1, $e);
            }
        }

        // Just remove the related entities ! AND POUFF it has disappeared.
        $this->em->remove($booking);
        $this->em->flush();
    }

    /**
     * Returns wether an user is the organizer of the meeting or not.
     *
     * @param Booking $booking
     * @param User $user
     * @return boolean
     */
    public function isOrganizer(Booking $booking, User $user): bool
    {
        return !$this->filterParticipantWithUser($booking, $user)->getIsInvitation();
    }

    /**
     * Filter Participant(s) list with user.
     *
     * @param Booking $booking
     * @param User $user
     * @return Participant
     */
    public function filterParticipantWithUser(Booking $booking, User $user): Participant
    {
        /** @var ArrayCollection<Participant> $usersFiltered */
        $usersFiltered = $booking->getParticipants()->filter(function(Participant $participant) use($user){
            return $participant->getUser() === $user;
        });

        if($usersFiltered->isEmpty()){
            throw new \Exception("La réunion ne contient pas le participant ". $user);
        }

        return $usersFiltered->first();
    }

}