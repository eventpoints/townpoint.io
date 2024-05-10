<?php

namespace App\Service\RandomService\Contract;

interface RandomGeneratorInterface
{
    public function generate(): string;
}
