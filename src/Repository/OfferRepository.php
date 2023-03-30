<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Auction\Item;
use App\Entity\Auction\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offer>
 *
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    public function save(Offer $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(Offer $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function findByItem(Item $item, bool $isQuery)
    {
        $qb = $this->createQueryBuilder('offer');
        $qb->innerJoin('offer.owner', 'owner');
        $qb->innerJoin('offer.item', 'item');
        $qb->select('offer.price')->distinct();
        $qb->addSelect('owner.id, owner.firstName, owner.lastName')->distinct();
        $qb->addSelect('offer.createdAt');

        $qb->andWhere(
            $qb->expr()
                ->eq('offer.item', ':item')
        )->setParameter('item', $item->getId(), 'uuid');

        $qb->orderBy('offer.price', 'DESC');
        $qb->groupBy('offer.price, offer.createdAt, owner.id');

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()
            ->getResult();
    }
}
