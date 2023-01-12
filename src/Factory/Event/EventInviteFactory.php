<?php

declare(strict_types = 1);

namespace App\Factory\Event;

use App\Entity\Event\Event;
use App\Entity\Event\EventInvite;
use App\Entity\User;

class EventInviteFactory
{
    public function create(Event $event, User $owner): EventInvite
    {
        $eventInvite = new EventInvite();
        $eventInvite->setEvent($event);
        $eventInvite->setOwner($owner);

        return $eventInvite;
    }
}
