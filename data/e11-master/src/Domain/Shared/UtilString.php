<?php

namespace App\Domain\Shared;

/**
 * This class is not part of "Domain". But we need to put it somewhere and I like this place a lot.
 * I'm sure this class likes to be here too.
 */
class UtilString{

    /**
     * Generates a random string.
     *
     * @param integer $length
     * @return string
     */
    public static function randomString(int $length = 25): string
    {
        return substr(bin2hex(random_bytes((int) ceil($length / 2))), 0, $length);
    }

}