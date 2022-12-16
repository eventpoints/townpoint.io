<?php

declare(strict_types = 1);

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
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(PollOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function getPollPercentages(Poll $poll): mixed
    {
        $qb = $this->createQueryBuilder('o');
        $qb->leftJoin('o.pollAnswers', 'a');

        $optionCount = $qb->expr()
            ->count('a.id') . 'AS option_as_answer_count';
        $totalAnswerCount = $poll->getPollAnswers()
            ->count() . 'AS total_answer_count';
        $percentage = $qb->expr()
            ->quot($qb->expr() ->count('a.id'), $poll->getPollAnswers() ->count()) . '* 100 AS percentage';

        $qb->addSelect($optionCount, $totalAnswerCount, $percentage);

        $qb->andWhere($qb->expr() ->eq('o.poll', ':id'))
            ->setParameter('id', $poll->getId(), 'uuid');

        $qb->groupBy('o.id');

        return $qb->getQuery()
            ->getResult();
    }
}
