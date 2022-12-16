<?php

declare(strict_types = 1);

namespace App\Builder;

use App\Builder\Contract\StatementBuilderInterface;
use App\Builder\Contract\StatementInterface;
use App\Entity\Statement;
use App\Entity\User;

class StatementBuilder implements StatementBuilderInterface, StatementInterface
{
    private Statement $statement;

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): void
    {
        $this->statement = new Statement();
    }

    public function getResult(): Statement
    {
        $result = $this->statement;
        $this->reset();

        return $result;
    }

    public function setPhoto(null|string $photo): void
    {
        $this->statement->setPhoto($photo);
    }

    public function setContent(string $content): void
    {
        $this->statement->setContent($content);
    }

    public function setOwner(User $user): void
    {
        $this->statement->setOwner($user);
    }
}
