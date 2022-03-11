<?php

namespace App\DataFixtures;

use App\Domain\Booking\Entity\Establishment;
use App\Domain\Booking\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EstablishmentFixtures extends Fixture
{
    private const ESEO_ANGERS_ROOMS = [
        'AFRIQUE'               => ['code' => 'B110', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '18:00:00'],
        'AMERIQUE'              => ['code' => 'C109', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '18:00:00'],
        'ANTARCTIQUE'           => ['code' => 'A115', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '18:00:00'],
        'ASIE'                  => ['code' => 'B213', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '18:00:00'],
        'COLLABORATIVE ROOM'    => ['code' => 'A022', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '20:00:00'],
        'ESPACE SAINT-AUBIN'    => ['code' => 'C304', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '18:00:00'],
        'OCEANIE'               => ['code' => 'C001', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '18:00:00'],
        'LAUM - UMR CNRS 6613'  => ['code' => 'B212', 'isBookable' => false, 'timeOpen' => '07:00:00', 'timeClose' => '20:00:00']
    ];

    private const ESEO_VELIZY_ROOMS = [
        'BOSTON'                => ['code' => 'S03', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '18:00:00'],
        'DAKAR'                 => ['code' => 'S1.8', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '18:00:00'],
        'HONG KONG'             => ['code' => '1.11', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '18:00:00'],
        'RIO'                   => ['code' => 'S2.7', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '18:00:00'],
        'SYDNEY'                => ['code' => 'S2.10', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '20:00:00'],
    ];

    private const ESEO_DIJON_ROOMS = [
        'SALLE DE REUNION 118 (Commun ESTP)'
                                => ['code' => '118', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '18:00:00'],
        'SALLE PROJET R03'      => ['code' => 'R03', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '18:00:00'],
        'SALLE PROJET R08'      => ['code' => 'R08', 'isBookable' => true, 'timeOpen' => '08:00:00', 'timeClose' => '18:00:00'],
    ];

    public const ANGERS_REFERENCE   = 'eseo-angers';
    public const VELIZY_REFERENCE   = 'eseo-velizy';
    public const DIJON_REFERENCE    = 'eseo-dijon';

    public function load(ObjectManager $manager): void
    {
        $ESEO = new Establishment();
        $ESEO->setAddress("10 Boulevard Jeanneteau, 49000 Angers, France");
        $ESEO->setName("ESEO Angers");
        $ESEO->setTimeopen(new \DateTime('08:00:00'));
        $ESEO->setTimeclose(new \DateTime('18:00:00'));
        
        $manager->persist($ESEO);

        // Creates Rooms for ESEO
        foreach (self::ESEO_ANGERS_ROOMS as $name => $options) {
            $room = new Room();
            $room->setEstablishment($ESEO);
            $room->setIdNumber($options['code']);
            $room->setName($name);
            $room->setIsBookable($options['isBookable']);
            $room->setTimeOpen(new \DateTime($options['timeOpen']));
            $room->setTimeClose(new \DateTime($options['timeClose']));
            $room->setMaxTime('4h');

            $manager->persist($room);
        }

        $this->addReference(self::ANGERS_REFERENCE, $ESEO);

        $ESEO = new Establishment();
        $ESEO->setAddress("13 Avenue Morane Saulnier, 78140 VÉLIZY VILLACOUBLAY, France");
        $ESEO->setName("ESEO Velizy");
        $ESEO->setTimeopen(new \DateTime('08:00:00'));
        $ESEO->setTimeclose(new \DateTime('18:00:00'));
        
        $manager->persist($ESEO);

        // Creates Rooms for ESEO
        foreach (self::ESEO_VELIZY_ROOMS as $name => $options) {
            $room = new Room();
            $room->setEstablishment($ESEO);
            $room->setIdNumber($options['code']);
            $room->setName($name);
            $room->setIsBookable($options['isBookable']);
            $room->setTimeOpen(new \DateTime($options['timeOpen']));
            $room->setTimeClose(new \DateTime($options['timeClose']));
            $room->setMaxTime('4h');

            $manager->persist($room);
        }

        $this->addReference(self::VELIZY_REFERENCE, $ESEO);

        $ESEO = new Establishment();
        $ESEO->setAddress("6 place des Savoirs, 21000 DIJON, France");
        $ESEO->setName("ESEO Dijon");
        $ESEO->setTimeopen(new \DateTime('08:00:00'));
        $ESEO->setTimeclose(new \DateTime('17:00:00')); # Eux ils ferment plus tôt parce qu'à l'est le soleil se couche plus tôt.
        
        $manager->persist($ESEO);

        // Creates Rooms for ESEO
        foreach (self::ESEO_DIJON_ROOMS as $name => $options) {
            $room = new Room();
            $room->setEstablishment($ESEO);
            $room->setIdNumber($options['code']);
            $room->setName($name);
            $room->setIsBookable($options['isBookable']);
            $room->setTimeOpen(new \DateTime($options['timeOpen']));
            $room->setTimeClose(new \DateTime($options['timeClose']));
            $room->setMaxTime('4h');

            $manager->persist($room);
        }

        $this->addReference(self::DIJON_REFERENCE, $ESEO);

        $manager->flush();
    }
}
