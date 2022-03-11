<?php

namespace App\Domain\Booking;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class EstablishmentFormDTO{

    #[Assert\NotNull(message: "Le nom de l'établissement ne peut pas être nul.")]
    public string $name;

    #[Assert\NotNull(message: "L'adresse de l'établissement ne peut pas être nulle.")]
    public string $address;

    
    /**
     * @var \DateTimeInterface
     */
    #[Assert\NotNull(message: "L'heure d'ouverture doit être précisée.")]
    #[Assert\Type(type: DateTimeInterface::class, message: "L'heure d'ouverture ne correspond à aucun format de date connu.")]
    public DateTimeInterface $timeOpen;

    /**
     * @var \DateTimeInterface
     */
    #[Assert\NotNull(message: "L'heure de fermeture doit être précisée.")]
    #[Assert\GreaterThan(propertyPath: 'timeOpen')]
    #[Assert\Type(type: DateTimeInterface::class, message: "L'heure de fermeture ne correspond à aucun format de date connu.")]
    public DateTimeInterface $timeClose;

    #[Assert\NotNull(message: "Votre prénom ne peut pas être vide.")]
    #[Assert\Length(
        min: 1, minMessage: "Votre prénom doit comprendre au moins 1 caractère.",
        max: 255, maxMessage: "Votre prénom ne doit pas comporter plus de 255 caractères."
    )]
    public string $userName;

    #[Assert\NotNull(message: "Votre nom ne peut pas être vide.")]
    #[Assert\Length(
        min: 1, minMessage: "Votre nom doit comprendre au moins 1 caractère.", max: 255,
        maxMessage: "Votre nom ne doit pas comporter plus de 255 caractères."
    )]
    public string $userSurname;

    #[Assert\NotNull(message: "Votre email ne peut pas être vide.")]
    #[Assert\Length(
        min: 1, minMessage: "Votre email doit comprendre au moins 1 caractère.",
        max: 255, maxMessage: "Votre email ne doit pas comporter plus de 255 caractères."
    )]
    public string $userEmail;

    #[Assert\NotNull(message: "Votre mot de passe ne peut pas être vide.")]
    #[Assert\Length(
        min: 6, minMessage: "Votre mot de passe doit comprendre au moins 6 caractères.",
        max: 64, maxMessage: "Votre mot de passe ne doit pas comporter plus de 64 caractères."
    )]
    public string $userPassword;
}