<?php

namespace App\Domain\Booking;

use DateTime;
use DateTimeInterface;

use App\Domain\Booking\Entity\Room;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThan;

/**
 * Booking Form DTO.
 */
class BookingFormDTO{

    /**
     * Date of the actual booking.
     *
     * @var DateTime
     */
    #[Assert\NotNull(message: 'La date de la réservation ne peut pas être nulle.')]
    #[Assert\Type(type: DateTime::class)]
    #[Assert\GreaterThanOrEqual('today')]
    private $date;

    /**
     * @var \DateTimeInterface
     */
    #[Assert\NotNull(message: "L'heure de début de la réunion doit être précisée.")]
    #[Assert\Type(type: DateTimeInterface::class, message: "L'heure de début de réunion ne correspond à aucun format de date connu.")]
    private DateTimeInterface $timeStart;

    /**
     * @var \DateTimeInterface
     */
    #[Assert\NotNull(message: "L'heure de fin de la réunion doit être précisée.")]
    #[Assert\GreaterThan(propertyPath: 'timeStart')]
    #[Assert\Type(type: DateTimeInterface::class, message: "L'heure de fin de réunion ne correspond à aucun format de date connu.")]
    private DateTimeInterface $timeEnd;

    /**
     * @var Room
     */
    #[Assert\NotNull(message: "Une salle doit être spécifiée.")]
    private Room $room;

    /**
     * @var User[]
     *
     * @var ArrayCollection
     */
    private ArrayCollection $participants;


    /**
     * Get date of the actual booking.
     *
     * @return  DateTime
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set date of the actual booking.
     *
     * @param  DateTime  $date  Date of the actual booking.
     *
     * @return  self
     */ 
    public function setDate(DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of timeStart
     *
     * @return  \DateTimeInterface
     */ 
    public function getTimeStart()
    {
        return $this->timeStart;
    }

    /**
     * Set the value of timeStart
     *
     * @param  \DateTimeInterface  $timeStart
     *
     * @return  self
     */ 
    public function setTimeStart(\DateTimeInterface $timeStart)
    {
        $this->timeStart = $timeStart;

        return $this;
    }

    /**
     * Get the value of timeEnd
     *
     * @return  \DateTimeInterface
     */ 
    public function getTimeEnd()
    {
        return $this->timeEnd;
    }

    /**
     * Set the value of timeEnd
     *
     * @param  \DateTimeInterface  $timeEnd
     *
     * @return  self
     */ 
    public function setTimeEnd(\DateTimeInterface $timeEnd)
    {
        $this->timeEnd = $timeEnd;

        return $this;
    }

    /**
     * Get the value of room
     *
     * @return  Room
     */ 
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set the value of room
     *
     * @param  Room  $room
     *
     * @return  self
     */ 
    public function setRoom(Room $room)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get the value of participants
     *
     * @return  User[]
     */ 
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * Set the value of participants
     *
     * @param  User[]  $participants
     *
     * @return  self
     */ 
    public function setParticipants(ArrayCollection $participants)
    {
        $this->participants = $participants;

        return $this;
    }
}