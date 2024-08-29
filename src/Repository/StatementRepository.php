<?php

namespace App\Repository;

use App\DataTransferObject\StatementFilterDto;
use App\Entity\Statement;
use App\Entity\Town;
use App\Enum\StatementTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Order;
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

    public function save(Statement $entity, bool $flush = false): void
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

    /**
     * @return array<int, Statement>
     */
    public function findByFilterAndTown(StatementFilterDto $statementFilterDto, Town $town): array
    {
        $qb = $this->createQueryBuilder('statement');

        $qb->andWhere(
            $qb->expr()->eq('statement.town', ':town')
        )->setParameter('town', $town->getId());

        if ($statementFilterDto->getKeyword() !== null && $statementFilterDto->getKeyword() !== '' && $statementFilterDto->getKeyword() !== '0') {
            $qb->andWhere(
                $qb->expr()->like($qb->expr()->lower('statement.content'), ':keyword')
            )->setParameter('keyword', '%' . strtolower($statementFilterDto->getKeyword()) . '%');
        }

        if ($statementFilterDto->getType() instanceof StatementTypeEnum) {
            $qb->andWhere(
                $qb->expr()->eq('statement.type', ':type')
            )->setParameter('type', $statementFilterDto->getType());
        }

        $qb->andWhere(
            $qb->expr()->isNull('statement.statement')
        );

        $qb->setMaxResults(50);
        $qb->orderBy('statement.createdAt', Order::Descending->value);

        return $qb->getQuery()->getResult();
    }
}
