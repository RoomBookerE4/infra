<?php

namespace App\Domain\Booking;

use App\Domain\Auth\Entity\User;
use App\Domain\Auth\UserRoles;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Booking\Entity\Establishment;
use App\Domain\Booking\EstablishmentFormDTO;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EstablishmentService{

    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
        
    }

    public function create(EstablishmentFormDTO $dto): Establishment
    {
        $establishment = new Establishment();

        $establishment->setName($dto->name);
        $establishment->setAddress($dto->address);
        $establishment->setTimeopen($dto->timeOpen);
        $establishment->setTimeclose($dto->timeClose);

        $admin = new User();
        $admin->setEstablishment($establishment);
        $admin->setName($dto->userName);
        $admin->setSurname($dto->userSurname);
        $admin->setEmail($dto->userEmail);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $dto->userPassword));
        $admin->setRole(UserRoles::ADMIN);

        $this->em->persist($establishment);
        $this->em->persist($admin);
        $this->em->flush();

        return $establishment;
    }

}