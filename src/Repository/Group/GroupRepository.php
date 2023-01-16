<?php

declare(strict_types = 1);

namespace App\Repository\Group;

use App\DataTransferObjects\GroupFilterDto;
use App\Entity\Group\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Group>
 *
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function save(Group $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(Group $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function findByGroupFilter(GroupFilterDto $groupFilterDto, bool $isQuery = false): mixed
    {
        $qb = $this->createQueryBuilder('g');

        $qb->andWhere($qb->expr() ->eq('g.isVisible', ':true'))
            ->setParameter('true', true);

        if ($groupFilterDto->getTitle()) {
            $qb->andWhere(
                $qb->expr()
                    ->like('g.title', ':title')
            )->setParameter('title', '%' . $groupFilterDto->getTitle() . '%');
        }

        if ($groupFilterDto->getType()) {
            $qb->andWhere(
                $qb->expr()
                    ->like('g.type', ':type')
            )->setParameter('type', '%' . $groupFilterDto->getType() . '%');
        }

        if ($groupFilterDto->getCountry()) {
            $qb->andWhere(
                $qb->expr()
                    ->eq('g.country', ':country')
            )->setParameter('country', $groupFilterDto->getCountry());
        }

        $qb->orderBy('g.createdAt', 'DESC');

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()
            ->getResult();
    }
}
