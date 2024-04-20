<?php

declare(strict_types=1);

namespace App\Service\UserInterfaceHelperService;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserInterfaceHelperService
{
    public function getUser(null|UserInterface $userInterface): null|User
    {
        if ($userInterface instanceof User) {
            return $userInterface;
        };

        return null;
    }
}
