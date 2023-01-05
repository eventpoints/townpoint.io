<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Entity\Event;
use App\Entity\EventRequest;
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
