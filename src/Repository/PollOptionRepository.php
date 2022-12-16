<?php

namespace App\Repository;

use App\Entity\Poll;
use App\Entity\PollOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PollOption>
 *
 * @method PollOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method PollOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method PollOption[]    findAll()
 * @method PollOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PollOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PollOption::class);
    }

    public function add(PollOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PollOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PollOption[] Returns an array of PollOption objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PollOption
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function getPollPercentages(Poll $poll)
    {
        $qb = $this->createQueryBuilder('o');
        $qb->leftJoin('o.pollAnswers', 'a');

        $optionCount = $qb->expr()->count('a.id') . 'AS option_as_answer_count';
        $totalAnswerCount = $poll->getPollAnswers()->count() . 'AS total_answer_count';
        $percentage = $qb->expr()->quot(
            $qb->expr()->count('a.id'), $poll->getPollAnswers()->count()
        ) . '* 100 AS percentage';

        $qb->addSelect(
            $optionCount,
            $totalAnswerCount,
            $percentage
        );

        $qb->andWhere(
            $qb->expr()->eq('o.poll', ':id')
        )->setParameter('id', $poll->getId(), 'uuid');

        $qb->groupBy('o.id');

        return $qb->getQuery()->getResult();
    }
}
