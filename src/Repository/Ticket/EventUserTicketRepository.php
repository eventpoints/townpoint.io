<?php

declare(strict_types = 1);

namespace App\Repository\Ticket;

use App\Entity\Event\EventUserTicket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventUserTicket>
 *
 * @method EventUserTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventUserTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventUserTicket[]    findAll()
 * @method EventUserTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventUserTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventUserTicket::class);
    }

    public function save(EventUserTicket $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(EventUserTicket $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

//    /**
//     * @return EventTicket[] Returns an array of EventTicket objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EventTicket
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
