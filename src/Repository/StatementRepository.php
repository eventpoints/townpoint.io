<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Statement;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Statement>
 *
 * @method Statement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statement[]    findAll()
 * @method Statement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statement::class);
    }

    public function add(Statement $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(Statement $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function findByUser(User $user): mixed
    {
        $qb = $this->createQueryBuilder('s');
        $qb->addOrderBy('s.createdAt', 'DESC');
        $qb->andWhere($qb->expr() ->eq('s.owner', ':id'))
            ->setParameter('id', $user->getId(), 'uuid');

        return $qb->getQuery()
            ->getResult();
    }
}
