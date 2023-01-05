<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Entity\Event;
use App\Entity\EventUser;
use App\Entity\User;

class EventUserFactory
{
    public function create(User $user, Event $event): EventUser
    {
        $eventUser = new EventUser();
        $eventUser->setOwner($user);
        $eventUser->setEvent($event);

        return $eventUser;
    }
}
