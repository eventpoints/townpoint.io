<?php

namespace App\Factory;

use App\Entity\Listener;
use App\Entity\User;

class ListenerFactory
{

    public function create(
        User $owner,
        User $target
    ) : Listener
    {
        $listener = new Listener();
        $listener->setOwner($owner);
        $listener->setTarget($target);
        return $listener;
    }

}