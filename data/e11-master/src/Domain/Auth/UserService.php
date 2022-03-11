<?php

namespace App\Domain\Auth;

use LogicException;

use App\Domain\Auth\Entity\User;
use App\Domain\Auth\Repository\UserRepository;
use App\Domain\Shared\MailerService;
use App\Domain\Shared\UtilString;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Handle changes for a user.
 */
class UserService{

    /**
     * Dependy Injection. We need the entity Manager.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private MailerService $mailerService
    )
    {
        
    }

    /**
     * Create a user based on their name, defined role, password, or surname.
     *
     * @param [type] $name
     * @param [type] $role
     * @param [type] $password
     * @param [type] $surname
     * @return void
     */
    public function createUser(User $user): User
    {
        // Persist into database.
        $this->em->persist($user);
        // Flushing : refresh the objects with persisted changes.
        $this->em->flush();

        return $user;
    }

    /**
     * Delete a user following its id.
     *
     * @param integer $id
     * @return void
     */
    public function deleteUser(int $id): void
    {
        // Find the wanted user.
        $user = $this->userRepository->find($id);

        // If the user does not exist, we throw an exception.
        if($user === null){
            throw new EntityNotFoundException("L'utilisateur à supprimer n'existe pas.");
        }

        // Remove the user from the database AND flush the User object.
        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * Setup the password generation cycle for the specified user.
     * When a user wants to reset his password, we populate the passwordForgottenAt attribute, with the current value.
     * Once a user has asked to reset his password, he has 3 hours to do so.
     *
     * @param User $user
     * @return void
     */
    public function passwordForgotten(User $user): void
    {
        $user->setPasswordForgottenAt(new \DateTime('now + 3 hour'));
        $user->setResetToken(UtilString::randomString());

        // TODO : Send an email with the correct informations.
        $this->mailerService->sendEmail(
            $user->getEmail(),
            $user->getUserIdentifier(),
            "Mot de passe oublié",
            "Votre mot de passe oublié",
            "user/_mail_changePassword.html.twig",
            [
                'user' => $user,
                'tokenPassword' => $user->getResetToken()
            ]
        );

        $this->em->flush();
    }

    /**
     * Change the password.
     *
     * @param User User
     * @param string User plain password
     * @return void
     */
    public function changePassword(User $user, string $password): void
    {
        
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        // We reset theses attributes because now the user finally has a new password.
        $user->setPasswordForgottenAt(null);
        $user->setResetToken(null);

        $this->em->flush();
    }

    /**
     * Allow to find a user by its id.
     * Return a User entity, not a UserInterface object.
     *
     * @param integer $id
     * @return User
     */
    public function find(int $id): User
    {
        return $this->em->find(User::class, $id);
    }

    /**
     * Find a User by its email address.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    /**
     * Find a user with a specified resetToken.
     *
     * @param string $resetToken
     * @return User|null
     */
    public function findByResetToken(string $resetToken): ?User
    {
        return $this->userRepository->findOneBy(['resetToken' => $resetToken]);
    }

}