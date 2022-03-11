<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use App\Domain\Booking\EstablishmentFormDTO;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EstablishmentCreationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de l'établissement"
            ])
            ->add('address', TextType::class, [
                'label' => "Adresse de l'établissement"
            ])
            ->add('timeOpen', TimeType::class, [
                'label' => "Heure d'ouverture de l'établissement"
            ])
            ->add('timeClose', TimeType::class, [
                'label' => "Heure de fermeture de l'établissement"
            ])
            ->add('userName', TextType::class, [
                'label' => "Prénom"
            ])
            ->add('userSurname', TextType::class, [
                'label' => "Nom"
            ])
            ->add('userEmail', EmailType::class, [
                'label' => "Email"
            ])
            ->add('userPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes doivent correspondre.',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répétez le mot de passe'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Créer"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EstablishmentFormDTO::class
        ]);
    }
}
