<?php

declare(strict_types = 1);

namespace App\Service;

use App\Entity\User;
use App\Entity\View;
use App\Repository\ViewRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileViewService
{
    public function __construct(
        private readonly ViewRepository $viewRepository,
        private readonly Security $security
    ) {
    }

    public function view(User $user): void
    {
        $currentUser = $this->security->getUser();
        assert($currentUser instanceof User && $currentUser instanceof UserInterface);
        if ($currentUser === $user) {
            return;
        }

        $view = new View();
        $view->setOwner($currentUser);
        $view->setUser($user);

        $this->viewRepository->add($view, true);
    }
}
