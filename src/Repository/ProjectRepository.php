<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function save(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function findProjectsByUser(User $user, string $type = 'SPOT_LIGHT', bool $isQuery = false): mixed
    {
        $qb = $this->createQueryBuilder('project');

        $qb->andWhere(
            $qb->expr()->eq('project.owner', ':user')
        )->setParameter('user', $user->getId(), 'uuid');

        $qb->andWhere(
            $qb->expr()->eq('project.type', ':type')
        )->setParameter('type', $type);

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()->getResult();
    }
}
