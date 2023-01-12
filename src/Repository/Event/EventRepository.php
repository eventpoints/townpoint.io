<?php

declare(strict_types = 1);

namespace App\Repository\Event;

use App\DataTransferObjects\EventFilterDto;
use App\Entity\Event\Event;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function save(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function findByEventFilter(EventFilterDto $eventFilterDto): array
    {
        $qb = $this->createQueryBuilder('e');

        if ($eventFilterDto->getTitle()) {
            $qb->andWhere(
                $qb->expr()
                    ->like('e.title', ':title')
            )->setParameter('title', '%' . $eventFilterDto->getTitle() . '%');
        }

        if ($eventFilterDto->getAddress()) {
            $qb->andWhere(
                $qb->expr()
                    ->like('e.address', ':address')
            )->setParameter('address', '%' . $eventFilterDto->getAddress() . '%');
        }

        if ($eventFilterDto->getStartAt() instanceof DateTimeImmutable) {
            $qb->andWhere(
                $qb->expr()
                    ->gt('e.startAt', ':startAt')
            )->setParameter('startAt', $eventFilterDto->getStartAt(), Types::DATETIME_IMMUTABLE);
        }

        if ($eventFilterDto->getEndAt() instanceof DateTimeImmutable) {
            $qb->andWhere(
                $qb->expr()
                    ->lt('e.endAt', ':endAt')
            )->setParameter('endAt', $eventFilterDto->getEndAt(), Types::DATETIME_IMMUTABLE);
        }

        $qb->orderBy('e.createdAt', 'DESC');

        return $qb->getQuery()
            ->getResult();
    }
}
