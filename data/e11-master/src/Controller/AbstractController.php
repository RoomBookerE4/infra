<?php

namespace App\Controller;

use App\Domain\Auth\UserService;
use App\Domain\Auth\Entity\User;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController{

    protected function getUserEntity(): ?User
    {
        return $this->getUser();
    }

    protected function getUserOrThrow(): User
    {
        $user = $this->getUserEntity();
        if (!($user instanceof User)) {
            throw new AccessDeniedException();
        }

        return $user;
    }

}