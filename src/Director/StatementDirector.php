<?php

namespace App\Director;

use App\Builder\Contract\PollBuilderInterface;
use App\Builder\Contract\StatementBuilderInterface;
use App\Builder\Contract\StatementInterface;
use App\Entity\Poll;
use App\Entity\Statement;
use App\Entity\User;
use Symfony\Component\Form\FormInterface;

class StatementDirector
{

    public function makePoll(StatementBuilderInterface|PollBuilderInterface $pollBuilder, FormInterface $form, User $user): Poll
    {
        $pollBuilder->setMotion($form->get('motion')->getData());
        $pollBuilder->setOwner($user);
        $pollBuilder->setOptions($form->get('pollOptions')->getData());
        $pollBuilder->setEndAt($form->get('endAt')->getData());
        return $pollBuilder->getResult();
    }

    public function makeStatement(StatementBuilderInterface|StatementInterface $statementBuilder, FormInterface $form, User $user, null|string $image): Statement
    {
        $statementBuilder->setContent($form->get('content')->getData());
        $statementBuilder->setPhoto($image);
        $statementBuilder->setOwner($user);
        return $statementBuilder->getResult();
    }

}