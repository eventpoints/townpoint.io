<?php

namespace App\Factory;

use App\Entity\Duration;
use App\Entity\User;
use DateTimeImmutable;

class DurationFactory
{

    public function create(
        User $user,

    ) : Duration
    {
        $duration = new Duration();
        $duration->setOwner($user);

        return $duration;
    }

}