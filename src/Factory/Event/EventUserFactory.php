<?php

declare(strict_types = 1);

namespace App\Factory\Event;

use App\Entity\Event\Event;
use App\Entity\Event\EventUser;
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
