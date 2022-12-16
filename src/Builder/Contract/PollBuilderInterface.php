<?php

namespace App\Builder\Contract;

use App\Entity\User;
use DateTimeImmutable;

interface PollBuilderInterface
{
    public function setMotion(string $title);
    public function setEndAt(DateTimeImmutable $endAt);
    public function setOptions(array $pollOptions);
    public function setOwner(User $user);
}