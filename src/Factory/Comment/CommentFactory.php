<?php

declare(strict_types = 1);

namespace App\Factory\Comment;

use App\Entity\Comment;
use App\Entity\Event\Event;
use App\Entity\User;

class CommentFactory
{
    public function create(User $user, string $content): Comment
    {
        $comment = new Comment();
        $comment->setOwner($user);
        $comment->setContent($content);

        return $comment;
    }

    public function createEventComment(User $user, string $content, Event $event): Comment
    {
        $comment = $this->create($user, $content);
        $comment->setEvent($event);

        return $comment;
    }
}
