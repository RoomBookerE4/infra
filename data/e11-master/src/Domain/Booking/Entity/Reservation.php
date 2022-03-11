<?php

namespace App\Domain\Booking\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Booking\Entity\Room;
use \App\Domain\Booking\Entity\Participant;

/**
 * Reservation
 */
#[ORM\Entity(repositoryClass: \App\Domain\Booking\Repository\ReservationRepository::class)]
#[ORM\Table(name:"Reservation")]
#[ORM\Index(columns: ['idRoom'])]
class Reservation
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    private $id;

    /**
     * @var \DateTimeInterface
     */
    #[ORM\Column(name: 'timeStart', type: 'datetime', nullable: false)]
    private $timeStart;

    /**
     * @var \DateTimeInterface
     */
    #[ORM\Column(name: 'timeEnd', type: 'datetime', nullable: false)]
    private $timeEnd;

    /**
     * @var Room
     */
    #[ORM\ManyToOne(targetEntity: Room::class)]
    #[ORM\JoinColumn(name: 'idRoom', referencedColumnName: 'id')]
    private $room;

    #[ORM\OneToMany(mappedBy: 'reservation', targetEntity: Participant::class, orphanRemoval: true)]
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeStart(): ?\DateTimeInterface
    {
        return $this->timeStart;
    }

    public function setTimeStart(\DateTimeInterface $timeStart): self
    {
        $this->timeStart = $timeStart;

        return $this;
    }

    public function getTimeEnd(): ?\DateTimeInterface
    {
        return $this->timeEnd;
    }

    public function setTimeEnd(\DateTimeInterface $timeEnd): self
    {
        $this->timeEnd = $timeEnd;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setResa($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getResa() === $this) {
                $participant->setResa(null);
            }
        }

        return $this;
    }
}
