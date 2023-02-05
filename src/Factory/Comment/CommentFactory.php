<?php

declare(strict_types = 1);

namespace App\Factory\Comment;

use App\Entity\Comment;
use App\Entity\Event\Event;
use App\Entity\Group\Group;
use App\Entity\Market\Item;
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

    public function createGroupComment(User $user, string $content, Group $group): Comment
    {
        $comment = $this->create($user, $content);
        $comment->setGroup($group);

        return $comment;
    }

    public function createMarketItem(User $user, string $content, Item $item): Comment
    {
        $comment = $this->create($user, $content);
        $comment->setMarketItem($item);

        return $comment;
    }
}
