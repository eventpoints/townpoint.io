<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\View;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<View>
 *
 * @method View|null find($id, $lockMode = null, $lockVersion = null)
 * @method View|null findOneBy(array $criteria, array $orderBy = null)
 * @method View[]    findAll()
 * @method View[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, View::class);
    }

    public function add(View $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(View $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCurrentUser(User $user)
    {
        $qb = $this->createQueryBuilder('v');
        $qb->innerJoin('v.owner', 'o');
        $qb->innerJoin('v.user', 'u');

        $qb->select('u.id, o.id as owner, o.firstName, o.lastName, o.avatar, COUNT(o.id) as view_count');

        $qb->andWhere(
            $qb->expr()->eq('u.id', ':userId')
        )->setParameter('userId', $user->getId(), 'uuid');

        $qb->groupBy('u.id', 'o.firstName', 'o.lastName', 'o.avatar', 'owner');

        $qb->andWhere(
            $qb->expr()->between('v.createdAt', ':startDate', ':endDate')
        )->setParameter('startDate', Carbon::now()->subDay()->toDateTime())
            ->setParameter('endDate', Carbon::now()->toDateTime());

        return $qb->getQuery()->getResult();
    }

    public function createAvgUserViewsCountCriteria(User $user): mixed
    {
        $qb = $this->createQueryBuilder('v');
        $qb->innerJoin('v.user', 'u');
        $qb->innerJoin('v.owner', 'o');
        $qb->select([
            'COUNT(u.id) as user_count',
        ]);

        $qb->andWhere(
            $qb->expr()->eq('v.owner', ':userId')
        )->setParameter('userId', $user->getId(), 'uuid');

        $qb->groupBy('u.id');
        return $qb->getQuery()->getScalarResult();
    }

    public static function createViewsByUserCriteria(User $user): Criteria
    {
        $expressionBuilder = Criteria::expr();
        return Criteria::create()
            ->andWhere(
                $expressionBuilder->eq('user', $user)
            )->orderBy(['createdAt' => 'DESC']);
    }
}
