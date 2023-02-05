<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\ShouldNotHappenException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use function Doctrine\ORM\QueryBuilder;

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
    public function __construct(ManagerRegistry $registry,
    private readonly Security $security
    )
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
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

    public function findPostsByUser(User $user): mixed
    {
        $qb = $this->createQueryBuilder('u');
        $qb->join('u.polls', 'p');
        $qb->join('u.statements', 's');

        $qb->andWhere($qb->expr()->eq('s.owner', ':user'))
            ->setParameter('user', $user);

        $qb->andWhere($qb->expr()->eq('p.owner', ':user'))
            ->setParameter('user', $user);

        return $qb->getQuery()
            ->getResult();
    }

    public function findByFilters(
        null|string $firstName,
        null|string $lastName,
        null|int    $minAge,
        null|int    $maxAge,
        null|string $gender
    ): Query
    {
        $qb = $this->createQueryBuilder('u');

        if ($firstName) {
            $qb->andWhere(
                $qb->expr()
                    ->like('u.firstName', ':firstName')
            )->setParameter('firstName', '%' . $firstName . '%');
        }

        if ($lastName) {
            $qb->andWhere(
                $qb->expr()
                    ->like('u.lastName', ':lastName')
            )->setParameter('lastName', '%' . $lastName . '%');
        }

        if ($minAge && $maxAge) {
            $qb->andWhere(
                $qb->expr()
                    ->between('u.age', ':minAge', ':maxAge')
            )->setParameter('minAge', $minAge, Types::INTEGER)
                ->setParameter('maxAge', $maxAge, Types::INTEGER);
        }

        if ($gender) {
            $qb->andWhere($qb->expr()->eq('u.gender', ':gender'))
                ->setParameter('gender', $gender, Types::STRING);
        }

        return $qb->getQuery();
    }

    /**
     * @throws ShouldNotHappenException
     */
    public function findByKeyword(string $keyword, bool $isQuery = false) : mixed
    {
        $user = $this->security->getUser();

        if(!$user instanceof User){
            throw new ShouldNotHappenException('user required');
        }

        $qb = $this->createQueryBuilder('u');

        $qb->orWhere(
            $qb->expr()
                ->like('lower(u.firstName)', ':firstName')
        )->setParameter('firstName', '%' . strtolower($keyword) . '%');

        $qb->orWhere(
            $qb->expr()
                ->like('lower(u.lastName)', ':lastName')
        )->setParameter('lastName', '%' . strtolower($keyword) . '%');

        $qb->orWhere(
            $qb->expr()
                ->like('lower(u.email)', ':email')
        )->setParameter('email', '%' . strtolower($keyword) . '%');

        $qb->andWhere(
            $qb->expr()->eq('u.isVisible', ':true')
        )->setParameter('true', true);

        $qb->andWhere(
            $qb->expr()->not(
                $qb->expr()->eq('u.id', ':userId')
            )
        )->setParameter('userId', $user->getId(), 'uuid');

        if($isQuery){
            return $qb->getQuery();
        }

        return $qb->getQuery()->getResult();
    }
}
