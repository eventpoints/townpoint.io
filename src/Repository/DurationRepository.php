<?php

namespace App\Repository;

use App\Entity\Duration;
use App\Entity\User;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Duration>
 *
 * @method Duration|null find($id, $lockMode = null, $lockVersion = null)
 * @method Duration|null findOneBy(array $criteria, array $orderBy = null)
 * @method Duration[]    findAll()
 * @method Duration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Duration::class);
    }

    public function add(Duration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Duration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByTodaysDateAndUser(User $user): array
    {
        $startOfDay = Carbon::today()->startOfDay();
        $endOfDay = Carbon::today()->endOfDay();

        $qb = $this->createQueryBuilder('d');
        $qb->andWhere(
            $qb->expr()->eq('d.owner', ':user')
        )->setParameter('user', $user->getId(), 'uuid');

        $qb->andWhere(
            $qb->expr()->between('d.createdAt',  ":startOfDay", ":endOfDay")
        )->setParameter('startOfDay', $startOfDay->toDateTimeImmutable())
            ->setParameter('endOfDay', $endOfDay->toDateTimeImmutable());

        $qb->orderBy('d.createdAt', 'DESC');

        return $qb->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?Duration
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
