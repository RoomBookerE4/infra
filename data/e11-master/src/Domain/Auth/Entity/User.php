<?php

namespace App\Domain\Auth\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Auth\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use \App\Domain\Booking\Entity\Establishment;

#[ORM\Table(name:"User")]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    private $id;

    #[ORM\Column(type: 'string', length: 180)]
    private $name;

    #[ORM\Column(type: 'string')]
    private $role = '';

    private $roles = [];

    #[ORM\Column(name: 'password', type: 'string')]
    private $password;

    #[ORM\Column(name: 'email', type: 'string', length: 255, unique: true)]
    private $email;

    #[ORM\Column(name: "surname", type: "string", length: 255, nullable: false)]
    private $surname;

    #[ORM\ManyToOne(targetEntity: Establishment::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private $establishment;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $passwordForgottenAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $resetToken;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) sprintf("%s %s", $this->name, $this->surname);
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEstablishment(): ?Establishment
    {
        return $this->establishment;
    }

    public function setEstablishment(?Establishment $establishment): self
    {
        $this->establishment = $establishment;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getUserIdentifier();
    }

    public function getPasswordForgottenAt(): ?\DateTimeInterface
    {
        return $this->passwordForgottenAt;
    }

    public function setPasswordForgottenAt(?\DateTimeInterface $passwordForgottenAt): self
    {
        $this->passwordForgottenAt = $passwordForgottenAt;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }
}
