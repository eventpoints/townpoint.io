<?php

declare(strict_types = 1);

namespace App\Repository\Group;

use App\Entity\Group\GroupUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupUser>
 *
 * @method GroupUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupUser[]    findAll()
 * @method GroupUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupUser::class);
    }

    public function save(GroupUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(GroupUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }
}
