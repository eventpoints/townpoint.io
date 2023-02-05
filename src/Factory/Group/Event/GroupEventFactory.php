<?php

namespace App\Factory\Group\Event;

use App\Entity\Event\Event;
use App\Entity\Group\Group;
use App\Entity\Group\GroupEvent;

class GroupEventFactory
{
    public function create(
        Group $group,
        Event $event
    ) : GroupEvent
    {
        $groupEvent = new GroupEvent();
        $groupEvent->setEvent($event);
        $groupEvent->setGroup($group);
        return $groupEvent;
    }
}