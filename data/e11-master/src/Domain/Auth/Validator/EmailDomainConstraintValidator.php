<?php

namespace App\Domain\Auth\Validator;

use Symfony\Component\Validator\Constraint;
use App\Domain\Auth\Validator\EmailDomainConstraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EmailDomainConstraintValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof EmailDomainConstraint) {
            throw new UnexpectedTypeException($constraint, EmailDomainConstraint::class);
        }
        
        // Just explode the given email on each "@" and get the last element.
        $domain = @array_pop(explode('@', $value));

        if (!in_array($domain, $constraint->domains)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ email }}', $value)
                ->addViolation()
            ;
        }
    }

}