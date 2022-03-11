<?php

namespace App\Form;

use ReflectionClass;
use App\Domain\Auth\UserRoles;

use App\Domain\Auth\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * Form used for user creation and edition.
 */
class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('role', ChoiceType::class, [
                'choices' => (new ReflectionClass(UserRoles::class))->getConstants(),
                'label' => 'Rôle'
            ])
            ->add('surname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
