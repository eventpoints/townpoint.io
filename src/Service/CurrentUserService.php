<?php

declare(strict_types = 1);

namespace App\Service;

use App\Entity\User;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

class CurrentUserService
{
    public function getCurrentUser(null|UserInterface $user): User
    {
        if (! $user instanceof User) {
            throw new Exception('UserInterface passed but no User object found');
        }

        return $user;
    }
}
