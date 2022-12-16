<?php

declare(strict_types = 1);

namespace App\Builder\Contract;

use App\Entity\User;

interface StatementInterface
{
    public function setContent(string $content): void;

    public function setPhoto(null|string $photo): void;

    public function setOwner(User $user): void;

    public function getResult(): mixed;
}
