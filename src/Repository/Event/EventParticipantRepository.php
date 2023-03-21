<?php

declare(strict_types=1);

namespace App\Repository\Event;

use App\Entity\Event\Event;
use App\Entity\Event\EventParticipant;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventParticipant>
 *
 * @method EventParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventParticipant[]    findAll()
 * @method EventParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventParticipant::class);
    }

    public function save(EventParticipant $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(EventParticipant $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function findByEvent(Event $event, bool $isQuery = false): mixed
    {
        $qb = $this->createQueryBuilder('eu');
        $qb->andWhere($qb->expr()->eq('eu.event', ':event'))
            ->setParameter('event', $event->getId(), 'uuid');

        $qb->orderBy('eu.createdAt', 'ASC');

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function findByUser(User $user, bool $isQuery = false): mixed
    {
        $qb = $this->createQueryBuilder('eventParticipant');

        $qb->andWhere(
            $qb->expr()->eq('eventParticipant.owner', ':user')
        )->setParameter('user', $user->getId(), 'uuid');

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()->getResult();
    }
}
