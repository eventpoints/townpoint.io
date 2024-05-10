<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversation>
 *
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function save(Conversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(Conversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByTwoParticipants(User $currentUser, User $target): null|Conversation
    {
        $qb = $this->createQueryBuilder('conversation');
        $qb->leftJoin('conversation.conversationParticipants', 'participant');

        $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->eq('participant.owner', ':currentUser'),
                $qb->expr()->eq('participant.owner', ':target')
            )
        )->setParameters([
            'currentUser' => $currentUser->getId(),
            'target' => $target->getId(),
        ]);

        $qb->groupBy('conversation.id')
            ->having('COUNT(DISTINCT participant.owner) = 2');

        return $qb->getQuery()->getOneOrNullResult();
    }
}
