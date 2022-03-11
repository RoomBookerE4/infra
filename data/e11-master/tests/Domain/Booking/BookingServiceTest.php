<?php

namespace App\Tests\Domain\Booking;

use App\Domain\Auth\Entity\User;
use App\Domain\Auth\UserRoles;
use App\Domain\Booking\BookingFormDTO;
use App\Domain\Booking\BookingService;
use App\Domain\Booking\Entity\Establishment;
use App\Domain\Booking\Entity\Room;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookingServiceTest extends KernelTestCase
{

    private Establishment $establishment;
    private User $organizer;
    private User $participant;
    private Room $room;

    public function setUp(): void
    {
        parent::setUp();
        $this->establishment = new Establishment();
        $this->establishment->setName('ESEO Angers');
        $this->establishment->setTimeclose(new DateTime('18:00:00'));
        $this->establishment->setTimeopen(new DateTime('08:00:00'));
        $this->establishment->setAddress('10, Boulevard Jean Jeanneteau, 49000 Angers');
        
        $this->organizer = new User();
        $this->organizer->setName("Orga");
        $this->organizer->setSurname("Nizer");
        $this->organizer->setEmail("orga.nizer@reseau.eseo.fr");
        $this->organizer->setPassword("password");
        $this->organizer->setRole(UserRoles::STUDENT);
        $this->organizer->setEstablishment($this->establishment);

        $this->participant = new User();
        $this->participant->setName("Parti");
        $this->participant->setSurname("Cipant");
        $this->participant->setEmail("parti.cipant@reseau.eseo.fr");
        $this->participant->setPassword("password");
        $this->participant->setRole(UserRoles::STUDENT);
        $this->participant->setEstablishment($this->establishment);

        $this->room = new Room();
        $this->room->setEstablishment($this->establishment);
        $this->room->setFloor(0);
        $this->room->setIdNumber("A001");
        $this->room->setIsBookable(true);
    }

    public function testBookARoom(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        /** @var BookingService $bookingService */
        $bookingService = static::getContainer()->get(BookingService::class);

        $dto = new BookingFormDTO();
        $dto->setDate(new DateTime('2022-01-18'));
        $dto->setTimeStart(new DateTime('10:00:00'));
        $dto->setTimeEnd(new DateTime('11:00:00'));
        $dto->setRoom($this->room);
        $dto->setParticipants(new ArrayCollection([$this->participant]));

        $booking = $bookingService->book($dto, $this->organizer);
        
        $this->assertSame(new DateTime('2022-01-18 10:00:00'), $dto->getTimeStart(), "La date de début est correcte");
        $this->assertSame(new DateTime('2022-01-18 11:00:00'), $dto->getTimeEnd(), "La date de fin est correcte");
        $this->assertSame($this->room, $dto->getRoom(), "La salle réservée est correcte");
        
    }
}
