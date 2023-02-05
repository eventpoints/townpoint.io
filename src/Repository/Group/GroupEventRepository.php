<?php

namespace App\Repository\Group;

use App\Entity\Group\Group;
use App\Entity\Group\GroupEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupEvent>
 *
 * @method GroupEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupEvent[]    findAll()
 * @method GroupEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupEvent::class);
    }

    public function save(GroupEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(GroupEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByGroup(Group $group, bool $isQuery = false) : mixed
    {
        $qb = $this->createQueryBuilder('ge');
        $qb->andWhere($qb->expr()->eq('ge.group', ':group'))
            ->setParameter('group', $group->getId(), 'uuid');

        $qb->leftJoin('ge.event', 'e');
        $qb->orderBy('e.createdAt', 'ASC');

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()
            ->getResult();
    }
}
