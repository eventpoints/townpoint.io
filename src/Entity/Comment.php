<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Entity\Event\Event;
use App\Entity\Group\Group;
use App\Entity\Auction\Item;
use App\Repository\CommentRepository;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private ?Uuid $id;

    #[ORM\Column(type: Types::TEXT)]
    private string $content;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private User $owner;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Group $group = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Item $marketItem = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?User $post = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable|CarbonImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function getMarketItem(): ?Item
    {
        return $this->marketItem;
    }

    public function setMarketItem(?Item $marketItem): self
    {
        $this->marketItem = $marketItem;

        return $this;
    }

    public function getPost(): ?User
    {
        return $this->post;
    }

    public function setPost(?User $post): self
    {
        $this->post = $post;

        return $this;
    }
}
