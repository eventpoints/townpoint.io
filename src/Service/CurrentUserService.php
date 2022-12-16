<?php

namespace App\Service;

use App\Entity\User;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

class CurrentUserService
{

    /**
     * @throws Exception
     */
    public function getCurrentUser(null|UserInterface $user) : null|User
    {
        if(!$user instanceof User){
            return null;
        }

        return $user;
    }

}