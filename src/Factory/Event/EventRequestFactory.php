<?php

declare(strict_types = 1);

namespace App\Factory\Event;

use App\Entity\Event\Event;
use App\Entity\Event\EventRequest;
use App\Entity\User;

class EventRequestFactory
{
    public function create(Event $event, User $user): EventRequest
    {
        $eventRequest = new EventRequest();
        $eventRequest->setEvent($event);
        $eventRequest->setOwner($user);

        return $eventRequest;
    }
}
