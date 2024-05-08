<?php

namespace App\Repository;

use App\Entity\ProfileView;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Order;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProfileView>
 *
 * @method ProfileView|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfileView|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfileView[]    findAll()
 * @method ProfileView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfileView::class);
    }

    public function save(ProfileView $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProfileView $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<int, array<mixed>>
     */
    public function findByTargetUser(User $user): array
    {
        $qb = $this->createQueryBuilder('profile_view');
        $qb->leftJoin('profile_view.owner', 'owner');

        $qb->select('owner.id, owner.handle, owner.firstName, owner.lastName, owner.avatar, owner.lastActiveAt, COUNT(profile_view.id) AS viewCount')
            ->andWhere($qb->expr()->eq('profile_view.target', ':user'))
            ->setParameter('user', $user->getId(), 'uuid')
            ->groupBy('owner.id', 'owner.handle', 'owner.firstName', 'owner.lastName', 'owner.avatar', 'owner.lastActiveAt');

        $qb->orderBy('owner.lastActiveAt', Order::Descending->value);
        $qb->setMaxResults(30);
        $queryResult = $qb->getQuery()->getResult();

        $formattedResult = [];
        foreach ($queryResult as $row) {
            $formattedResult[] = [
                'owner' => [
                    'id' => $row['id'],
                    'handle' => $row['handle'],
                    'avatar' => $row['avatar'],
                    'firstName' => $row['firstName'],
                    'lastName' => $row['lastName'],
                    'lastActiveAt' => $row['lastActiveAt'],
                ],
                'count' => (int) $row['viewCount'],
            ];
        }

        return $formattedResult;
    }
}
