<?php

declare(strict_types = 1);

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

    public function reset(): void
    {
        $this->poll = new Poll();
    }

    public function getResult(): Poll
    {
        $result = $this->poll;
        $this->reset();

        return $result;
    }

    public function setMotion(string $motion): void
    {
        $this->poll->setMotion($motion);
    }

    public function setEndAt(DateTimeImmutable $endAt): void
    {
        $this->poll->setEndAt($endAt);
    }

    public function setOptions(array $pollOptions): void
    {
        foreach ($pollOptions as $option) {
            $this->poll->addPollOption($option);
        }
    }

    public function setOwner(User $user): void
    {
        $this->poll->setOwner($user);
    }
}
