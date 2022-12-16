<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class PostRepository
{

    public function __construct(
        private readonly StatementRepository    $statementRepository,
        private readonly PollRepository         $pollRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function findPostsByUser(User $user): mixed
    {
        $statements = $this->statementRepository->findByUser($user);
        $polls = $this->pollRepository->findByUser($user);

        $posts = new ArrayCollection(
            array_merge($statements, $polls)
        );

        $criteria = Criteria::create()->orderBy(['createdAt' => 'DESC']);
        return $posts->matching($criteria);
    }

}