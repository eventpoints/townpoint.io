<?php

namespace App\Factory\Event;

use App\Entity\Event\Event;
use App\Entity\Event\EventRejection;
use App\Entity\User;

class EventRejectionFactory
{

    public function create(Event $event, User $user) : EventRejection
    {
        $eventRejection = new EventRejection();
        $eventRejection->setEvent($event);
        $eventRejection->setOwner($user);

        return $eventRejection;
    }

}