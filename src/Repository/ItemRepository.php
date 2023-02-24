<?php

declare(strict_types = 1);

namespace App\Repository;

use App\DataTransferObjects\MarketItemFilterDto;
use App\Entity\Market\Item;
use App\Entity\User;
use App\Exception\ShouldNotHappenException;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Item>
 *
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly Security $security
    ) {
        parent::__construct($registry, Item::class);
    }

    public function save(Item $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(Item $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function findByFilter(MarketItemFilterDto $marketItemFilterDto, bool $isQuery = false): mixed
    {
        $user = $this->security->getUser();

        if (! $user instanceof User) {
            throw new ShouldNotHappenException('user required');
        }

        $qb = $this->createQueryBuilder('i');

        $endDate = Carbon::now()->addMonth();
        $qb->andWhere(
            $qb->expr()->lte('i.createdAt', ':endDate')
        )->setParameter('endDate', $endDate->toDateTime(), Types::DATETIME_MUTABLE);

        if ($marketItemFilterDto->getTitle()) {
            $qb->andWhere(
                $qb->expr()
                    ->like('LOWER(i.title)', ':title')
            )->setParameter('title', '%' . strtolower($marketItemFilterDto->getTitle()) . '%');
        }

        if ($marketItemFilterDto->getMinPrice()) {
            $qb->andWhere(
                $qb->expr()
                    ->gte('i.price', ':price')
            )->setParameter('price', (float)$marketItemFilterDto->getMinPrice());
        }

        if ($marketItemFilterDto->getMaxPrice()) {
            $qb->andWhere(
                $qb->expr()
                    ->lte('i.price', ':price')
            )->setParameter('price', (float)$marketItemFilterDto->getMaxPrice());
        }

        if ($marketItemFilterDto->getCurrency()) {
            $qb->andWhere(
                $qb->expr()
                    ->eq('i.currency', ':currency')
            )->setParameter('currency', $marketItemFilterDto->getCurrency());
        }

        if ($marketItemFilterDto->getCondition()) {
            $qb->andWhere(
                $qb->expr()
                    ->eq('i.condition', ':condition')
            )->setParameter('condition', $marketItemFilterDto->getCondition());
        }

        $qb->orderBy('i.createdAt', 'ASC');

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function findByUser(User $user, bool $isQuery = false)
    {

        $qb = $this->createQueryBuilder('i');

        $qb->andWhere(
            $qb->expr()->eq('i.owner', ':owner')
        )->setParameter('owner', $user->getId(), 'uuid');

        $qb->orderBy('i.createdAt', 'ASC');

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()
            ->getResult();
    }
}
