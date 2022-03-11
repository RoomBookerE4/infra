<?php

namespace App\Domain\Auth;

/**
 * This class handles the possible roles for a user.
 * Each constant is linked to a role in order to unify the practices.
 */
abstract class UserRoles{

    public const STUDENT    = 'student';
    public const TEACHER    = 'teacher';
    public const ADMIN      = 'administrator';

}