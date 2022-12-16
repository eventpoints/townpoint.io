<?php

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

    public function reset()
    {
        $this->statement = new Statement();
    }


    public function getResult(): Statement
    {
        $result = $this->statement;
        $this->reset();
        return $result;
    }

    public function setPhoto(null|string $photo)
    {
        $this->statement->setPhoto($photo);
    }

    public function setContent(string $content)
    {
        $this->statement->setContent($content);
    }

    public function setOwner(User $user)
    {
        $this->statement->setOwner($user);
    }

}