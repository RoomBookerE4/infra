<?php

namespace App\Domain\Booking\Repository;

use DateTimeInterface;
use App\Domain\Auth\Entity\User;
use App\Domain\Booking\Entity\Room;
use App\Domain\Booking\Entity\Booking;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    /**
     * @return Booking[] Returns an array of Booking objects
     */
    public function findMeetings(?User $user = null, ?Room $room = null, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): ?array
    {
        $query = $this->createQueryBuilder('b');
        $query->leftJoin('App\Domain\Booking\Entity\Participant', 'p', Join::WITH, 'p.booking = b.id');

        if($user){
            $query->where('p.user = :user');
            $query->setParameter('user', $user);
        }
        if($room){
            $query->andWhere('b.room = :room');
            $query->setParameter('room', $room);
        }
        if($start){
            $query->andWhere('b.timeStart >= :start');
            $query->setParameter('start', $start);
        }
        if($end){
            $query->andWhere('b.timeEnd <= :end');
            $query->setParameter('end', $end);
        }

        $query->orderBy('b.timeStart', 'ASC');

        return $query->getQuery()->getResult();
    }
}
