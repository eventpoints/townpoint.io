<?php

declare(strict_types = 1);

namespace App\Repository\Event;

use App\Entity\Event\Event;
use App\Entity\Event\EventRejection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventRejection>
 *
 * @method EventRejection|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventRejection|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventRejection[]    findAll()
 * @method EventRejection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRejectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventRejection::class);
    }

    public function save(EventRejection $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(EventRejection $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

//    /**
//     * @return EventRejection[] Returns an array of EventRejection objects
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

//    public function findOneBySomeField($value): ?EventRejection
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findByEvent(Event $event, bool $isQuery = false): mixed
    {
        $qb = $this->createQueryBuilder('er');
        $qb->andWhere($qb->expr() ->eq('er.event', ':event'))
            ->setParameter('event', $event->getId(), 'uuid');

        $qb->orderBy('er.createdAt', 'ASC');

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()
            ->getResult();
    }
}
