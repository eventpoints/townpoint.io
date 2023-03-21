<?php

declare(strict_types = 1);

namespace App\Factory\Event;

use App\Entity\Event\Event;
use App\Entity\Event\EventParticipant;
use App\Entity\User;

class EventParticipantFactory
{
    public function create(User $user, Event $event): EventParticipant
    {
        $eventUser = new EventParticipant();
        $eventUser->setOwner($user);
        $eventUser->setEvent($event);

        return $eventUser;
    }
}
