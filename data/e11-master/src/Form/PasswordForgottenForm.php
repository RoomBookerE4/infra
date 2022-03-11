<?php

namespace App\Form;

use App\Domain\Auth\Validator\EmailDomainConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

class PasswordForgottenForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail associée à votre compte RoomBooker.',
                'constraints' => [new Email(), new EmailDomainConstraint()]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer un mail de réinitialisation'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
       // Nothing here for this form.
    }
}
