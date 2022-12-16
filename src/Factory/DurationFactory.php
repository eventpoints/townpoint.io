<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Entity\Duration;
use App\Entity\User;

class DurationFactory
{
    public function create(User $user): Duration
    {
        $duration = new Duration();
        $duration->setOwner($user);

        return $duration;
    }
}
