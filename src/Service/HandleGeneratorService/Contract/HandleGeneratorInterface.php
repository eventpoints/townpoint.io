<?php

namespace App\Service\HandleGeneratorService\Contract;

use App\Entity\User;

interface HandleGeneratorInterface
{
    public function generate(User $user): string;
}