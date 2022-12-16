<?php

namespace App\Builder;

use App\Builder\Contract\PollBuilderInterface;
use App\Builder\Contract\StatementBuilderInterface;
use App\Entity\Poll;
use App\Entity\User;
use DateTimeImmutable;

class PollBuilder implements StatementBuilderInterface, PollBuilderInterface
{
    private Poll $poll;

    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->poll = new Poll();
    }


    public function getResult(): Poll
    {
        $result = $this->poll;
        $this->reset();
        return $result;
    }

    public function setMotion(string $motion)
    {
        $this->poll->setMotion($motion);
    }

    public function setEndAt(DateTimeImmutable $endAt)
    {
        $this->poll->setEndAt($endAt);
    }

    public function setOptions(array $pollOptions)
    {
        foreach ($pollOptions as $option) {
            $this->poll->addPollOption($option);
        }
    }

    public function setOwner(User $user)
    {
        $this->poll->setOwner($user);
    }

}