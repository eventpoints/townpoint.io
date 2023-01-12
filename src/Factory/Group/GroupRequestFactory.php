<?php

declare(strict_types = 1);

namespace App\Factory\Group;

use App\Entity\Group\Group;
use App\Entity\Group\GroupRequest;
use App\Entity\User;

class GroupRequestFactory
{
    public function create(Group $group, User $owner): GroupRequest
    {
        $groupRequest = new GroupRequest();
        $groupRequest->setGroup($group);
        $groupRequest->setOwner($owner);

        return $groupRequest;
    }
}
