<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Event\Event;
use App\Entity\Group\Group;
use App\Entity\Market\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function save(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

//    /**
//     * @return Comment[] Returns an array of Comment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Comment
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findByEvent(Event $event, bool $isQuery = false): mixed
    {
        $qb = $this->createQueryBuilder('e');

        $qb->andWhere($qb->expr() ->eq('e.event', ':eventId'))
            ->setParameter('eventId', $event->getId(), 'uuid');

        $qb->orderBy('e.createdAt', 'DESC');

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function findByGroup(Group $group, bool $isQuery = false): mixed
    {
        $qb = $this->createQueryBuilder('c');

        $qb->andWhere($qb->expr() ->eq('c.group', ':groupId'))
            ->setParameter('groupId', $group->getId(), 'uuid');

        $qb->orderBy('c.createdAt', 'DESC');

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function findByMarketItem(Item $item, bool $isQuery = false): mixed
    {
        $qb = $this->createQueryBuilder('c');

        $qb->andWhere(
            $qb->expr()
                ->eq('c.marketItem', ':marketItem')
        )->setParameter('marketItem', $item->getId(), 'uuid');

        $qb->orderBy('c.createdAt', 'DESC');

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()
            ->getResult();
    }
}
