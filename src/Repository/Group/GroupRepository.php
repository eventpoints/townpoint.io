<?php

declare(strict_types = 1);

namespace App\Repository\Group;

use App\DataTransferObjects\GroupFilterDto;
use App\Entity\Group\Group;
use App\Entity\User;
use App\Exception\ShouldNotHappenException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

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
    public function __construct(
        ManagerRegistry $registry,
        private readonly Security $security
    ) {
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

        $qb->andWhere($qb->expr()->eq('g.isVisible', ':true'))
            ->setParameter('true', true);

        if ($groupFilterDto->getLanguage()) {
            $qb->andWhere(
                $qb->expr()
                    ->eq('g.language', ':language')
            )->setParameter(':language', $groupFilterDto->getLanguage());
        }

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

    public function findByKeyword(string $keyword, bool $isQuery = false): mixed
    {
        $user = $this->security->getUser();

        if (! $user instanceof User) {
            throw new ShouldNotHappenException('user required');
        }

        $qb = $this->createQueryBuilder('g');

        $qb->andWhere($qb->expr()->eq('g.isVisible', ':true'))
            ->setParameter('true', true);

        $qb->andWhere(
            $qb->expr()
                ->eq('g.language', ':language')
        )->setParameter(':language', $user->getLanguage());

        $qb->andWhere(
            $qb->expr()
                ->like('LOWER(g.title)', ':title')
        )->setParameter('title', '%' . strtolower($keyword) . '%');

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()
            ->getResult();
    }
}
