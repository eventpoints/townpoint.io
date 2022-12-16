<?php

namespace App\Builder\Contract;

interface StatementBuilderInterface
{
    public function reset();
    public function getResult() : mixed;
}