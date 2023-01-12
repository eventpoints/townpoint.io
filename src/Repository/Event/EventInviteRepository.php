<?php

declare(strict_types = 1);

namespace App\Repository\Event;

use App\Entity\Event\EventInvite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventInvite>
 *
 * @method EventInvite|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventInvite|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventInvite[]    findAll()
 * @method EventInvite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventInviteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventInvite::class);
    }

    public function save(EventInvite $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(EventInvite $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }
}
