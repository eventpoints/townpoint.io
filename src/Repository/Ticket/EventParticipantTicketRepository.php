<?php

declare(strict_types = 1);

namespace App\Repository\Ticket;

use App\Entity\Event\EventParticipantTicket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventParticipantTicket>
 *
 * @method EventParticipantTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventParticipantTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventParticipantTicket[]    findAll()
 * @method EventParticipantTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventParticipantTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventParticipantTicket::class);
    }

    public function save(EventParticipantTicket $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(EventParticipantTicket $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

}
