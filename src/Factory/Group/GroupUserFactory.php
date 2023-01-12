<?php

declare(strict_types = 1);

namespace App\Factory\Group;

use App\Entity\Group\Group;
use App\Entity\Group\GroupUser;
use App\Entity\User;

class GroupUserFactory
{
    final public const ROLE_GROUP_ADMIN = 'ROLE_GROUP_ADMIN';

    final public const ROLE_GROUP_USER = 'ROLE_GROUP_USER';

    public function create(User $user, Group $group, string $role = self::ROLE_GROUP_USER): GroupUser
    {
        $groupUser = new GroupUser();
        $groupUser->setOwner($user);
        $groupUser->setGroup($group);
        $groupUser->setRole($role);

        return $groupUser;
    }

    public function createdGroupAdmin(User $user, Group $group): GroupUser
    {
        return $this->create($user, $group, self::ROLE_GROUP_ADMIN);
    }
}
