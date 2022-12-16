<?php

namespace App\Repository;

use App\Entity\Interactor;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Interactor>
 *
 * @method Interactor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Interactor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Interactor[]    findAll()
 * @method Interactor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InteractorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interactor::class);
    }

    public function add(Interactor $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Interactor $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
