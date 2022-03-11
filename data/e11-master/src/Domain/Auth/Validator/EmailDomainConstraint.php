<?php

namespace App\Domain\Auth\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Defines this class as an usable attribute.
 */
#[\Attribute()]
class EmailDomainConstraint extends Constraint
{
    public string $message = "L'adresse mail fournie '{{ email }}' ne correspond pas à une adresse mail valide du réseau ESEO.";

    public array $domains = ["reseau.eseo.fr", "eseo.fr"];
}