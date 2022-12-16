<?php

declare(strict_types = 1);

namespace App\Builder\Contract;

use App\Entity\User;
use DateTimeImmutable;

interface PollBuilderInterface
{
    public function setMotion(string $title): void;

    public function setEndAt(DateTimeImmutable $endAt): void;

    public function setOptions(array $pollOptions): void;

    public function setOwner(User $user): void;

    public function getResult(): mixed;
}
