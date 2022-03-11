<?php

namespace App\Controller;

use App\Form\EstablishmentCreationForm;
use App\Form\EstablishmentCreationFormType;
use App\Domain\Booking\EstablishmentFormDTO;
use App\Domain\Booking\EstablishmentService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/registration', name: 'registration_')]
class RegistrationController extends AbstractController
{

    public function __construct(private EstablishmentService $establishmentService)
    {
        
    }

    #[Route('/establishment', name: 'establishment')]
    public function index(Request $request): Response
    {
        $dto = new EstablishmentFormDTO();
        $form = $this->createForm(EstablishmentCreationForm::class, $dto);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $dto = $form->getData();

            try{
                $this->establishmentService->create($dto);
                $this->addFlash('success', 'Reservation effectuée !');
            }
            catch(\Exception $e){
                $this->addFlash('error', $e->getMessage());
            }

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/establishment.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
