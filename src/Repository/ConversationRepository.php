<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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

    public function add(Conversation $entity, bool $flush = false): void
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

    public function findByTwoUsers(User $currentUser, User $user): Conversation|null
    {
        $qb = $this->createQueryBuilder('c');
        $qb->innerJoin('c.users', 'users');

        $qb->andWhere(
            $qb->expr()
                ->eq('c.owner', ':currentUser')
        )->setParameter('currentUser', $currentUser->getId(), 'uuid');

        $qb->andWhere($qb->expr() ->eq('users', ':user'))
            ->setParameter('user', $user->getId(), 'uuid');

        return $qb->getQuery()
            ->getOneOrNullResult();
    }

    public function findByUser(User $user): Query
    {
        $qb = $this->createQueryBuilder('c');
        $qb->innerJoin('c.users', 'users');

        $qb->andWhere($qb->expr()->in(':user', 'users'))
            ->setParameter('user', $user->getId(), 'uuid');

        return $qb->getQuery();
    }
}
