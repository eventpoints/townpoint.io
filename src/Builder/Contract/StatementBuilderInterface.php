<?php

declare(strict_types = 1);

namespace App\Builder\Contract;

interface StatementBuilderInterface
{
    public function reset(): void;

    public function getResult(): mixed;
}
