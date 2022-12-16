<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    public function createAvgUserViewsCountCriteria(User $user): mixed
    {
        $qb = $this->createQueryBuilder('u');
        $qb->innerJoin('u.viewed', 'v');
        $qb->select(
            $qb->expr()->quot(
                $qb->expr()->count('v.user'),
                $user->getViewed()->count()
            ) . 'as average_count'
        );

        $qb->andWhere(
            $qb->expr()->eq('v.owner', ':userId')
        )->setParameter('userId', $user->getId(), 'uuid');

        return $qb->getQuery()->getScalarResult();
    }

    public function findPostsByUser(User $user)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->join('u.polls', 'p');
        $qb->join('u.statements', 's');

        $qb->andWhere(
            $qb->expr()->eq('s.owner', ':user')
        )->setParameter('user', $user);

        $qb->andWhere(
            $qb->expr()->eq('p.owner', ':user')
        )->setParameter('user', $user);

        dd($qb->getQuery()->getResult());

        $qb->getQuery()->getResult();
    }

    public function findByFilters(
        null|string $firstName,
        null|string $lastName,
        null|int $minAge,
        null|int $maxAge,
        null|string $gender
    ) : Query
    {

        $qb = $this->createQueryBuilder('u');

        if($firstName){
            $qb->andWhere(
                $qb->expr()->like('u.firstName', ':firstName')
            )->setParameter('firstName', '%'.$firstName.'%');
        }

        if($lastName){
            $qb->andWhere(
                $qb->expr()->like('u.lastName', ':lastName')
            )->setParameter('lastName', '%'.$lastName.'%');
        }

        if ($minAge && $maxAge) {
            $qb->andWhere(
                $qb->expr()->between('u.age', ':minAge', ':maxAge')
            )->setParameter('minAge', $minAge, Types::INTEGER)
                ->setParameter('maxAge', $maxAge, Types::INTEGER);
        }

        if ($gender) {
            $qb->andWhere(
                $qb->expr()->eq('u.gender', ':gender')
            )
                ->setParameter('gender', $gender, Types::STRING);
        }

        return $qb->getQuery();
    }
}
