<?php

namespace App\DataFixtures;

use App\Domain\Auth\UserRoles;
use App\Domain\Auth\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        
    }

    public const USERS = [
        EstablishmentFixtures::ANGERS_REFERENCE => [
            [
                'email'     => 'student1_angers@reseau.eseo.fr',
                'role'      => UserRoles::STUDENT,
                'name'      => 'Student',
                'surname'   => '1'
            ],
            [
                'email'     => 'student2_angers@reseau.eseo.fr',
                'role'      => UserRoles::STUDENT,
                'name'      => 'Student',
                'surname'   => '2'
            ],
            [
                'email'     => 'student3_angers@reseau.eseo.fr',
                'role'      => UserRoles::STUDENT,
                'name'      => 'Student',
                'surname'   => '3'
            ],
            [
                'email'     => 'teacher1_angers@reseau.eseo.fr',
                'role'      => UserRoles::TEACHER,
                'name'      => 'Teacher',
                'surname'   => '1'
            ],
            [
                'email'     => 'teacher2_angers@reseau.eseo.fr',
                'role'      => UserRoles::TEACHER,
                'name'      => 'Teacher',
                'surname'   => '2'
            ],
            [
                'email'     => 'admin1_angers@reseau.eseo.fr',
                'role'      => UserRoles::ADMIN,
                'name'      => 'Admin',
                'surname'   => '1'
            ],
        ],
        EstablishmentFixtures::VELIZY_REFERENCE => [
            [
                'email'     => 'student1_velizy@reseau.eseo.fr',
                'role'      => UserRoles::STUDENT,
                'name'      => 'Student',
                'surname'   => '1'
            ],
            [
                'email'     => 'student2_velizy@reseau.eseo.fr',
                'role'      => UserRoles::STUDENT,
                'name'      => 'Student',
                'surname'   => '2'
            ],
            [
                'email'     => 'student3_velizy@reseau.eseo.fr',
                'role'      => UserRoles::STUDENT,
                'name'      => 'Student',
                'surname'   => '3'
            ],
            [
                'email'     => 'teacher1_velizy@reseau.eseo.fr',
                'role'      => UserRoles::TEACHER,
                'name'      => 'Teacher',
                'surname'   => '1'
            ],
            [
                'email'     => 'teacher2_velizy@reseau.eseo.fr',
                'role'      => UserRoles::TEACHER,
                'name'      => 'Teacher',
                'surname'   => '2'
            ],
            [
                'email'     => 'admin1_velizy@reseau.eseo.fr',
                'role'      => UserRoles::ADMIN,
                'name'      => 'Admin',
                'surname'   => '1'
            ],
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $establishment => $users) {
            foreach ($users as $user) {
                $entity = new User();
                $entity->setEmail($user['email']);
                $entity->setEstablishment($this->getReference($establishment));
                $entity->setName($user['name']);
                $entity->setSurname($user['surname']);
                $entity->setRole($user['role']);
                // By default for test purpose only, the password is the role name.
                $entity->setPassword($this->passwordHasher->hashPassword($entity, $user['role'])); 
                $manager->persist($entity);

                $this->addReference($user['email'], $entity);
            }
        }

        $manager->flush();
    }
}
