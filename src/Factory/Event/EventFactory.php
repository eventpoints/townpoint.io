<?php

declare(strict_types = 1);

namespace App\Factory\Event;

use App\Entity\Event\Event;
use App\Entity\User;
use DateTimeImmutable;

class EventFactory
{
    public function create(
        null|string $title = null,
        null|string $address = null,
        null|DateTimeImmutable $startAt = null,
        null|DateTimeImmutable $endAt = null,
        null|User $user = null,
        null|bool $isTicketed = null
    ): Event {
        $event = new Event();
        $event->setTitle($title);
        $event->setAddress($address);
        $event->setOwner($user);
        $event->setIsTicketed($isTicketed);
        $event->setStartAt($startAt);
        $event->setEndAt($endAt);

        return $event;
    }
}
