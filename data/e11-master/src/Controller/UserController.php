<?php

namespace App\Controller;

use App\Form\UserForm;
use App\Domain\Auth\Entity\User;
use App\Domain\Auth\UserService;
use App\Form\ChangePasswordForm;
use App\Form\PasswordForgottenForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_')] // Defines all the routes in this controller will be preceded by "/user".
class UserController extends AbstractController
{

    public function __construct(private UserService $userService)
    {
        
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/create', name: 'create', methods: ['POST', 'GET'])]
    public function create(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserForm::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $this->userService->createUser($user);

            return $this->render('user/success.html.twig', [
                'controller_name' => "success"
            ]);
        }

        return $this->render('user/new.html.twig', [
            'controller_name' => "J'ai créé Albert", 
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id): Response
    {
        $this->userService->deleteUser($id);

        return new Response();
    }

    #[Route('/password-forgotten', name: 'password_forgotten', methods: ['POST', 'GET'])]
    public function passwordForgotten(Request $request): Response
    {
        $form = $this->createForm(PasswordForgottenForm::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $currentUser = $this->userService->findByEmail($form['email']->getData());

            if($currentUser != null)
            {
                $this->userService->passwordForgotten($currentUser);

                $this->addFlash('success', 'Un mail a été envoyé à votre adresse.');

            }
            else{
                $this->addFlash('danger', 'Aucun utilisateur ne correspond à cette adresse mail.');
            }

            return $this->redirectToRoute('login');
        }

        return $this->render('user/passwordForgotten.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/change-password/{tokenPassword}', 'change_password')]
    public function changePassword(string $tokenPassword, Request $request): Response
    {
        // Find the corresponding user. Nevertheless, we need to be sure the password change has been asked within the last 3 hours.
        $user = $this->userService->findByResetToken($tokenPassword);
        if($user === null){
            $this->addFlash('danger', 'Réinitialisation impossible.');
            return $this->redirectToRoute('login');
        }

        if($user->getPasswordForgottenAt() < new \DateTime()){
            $this->addFlash('danger', 'Votre demande de changement de mot de passe a eu lieu il y a plus de 3 heures. Veuillez faire une nouvelle demande.');

            return $this->redirectToRoute('user_password_forgotten');
        }


        $form = $this->createForm(ChangePasswordForm::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $this->userService->changePassword($user, $form['password']->getData());

            $this->addFlash('success', 'Votre mot de passe a été changé.');

            return $this->redirectToRoute('login');
        }

        return $this->render('user/changePassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
