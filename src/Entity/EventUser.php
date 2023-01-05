<?php

namespace App\Entity;

use App\Repository\EventParticipantRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventParticipantRepository::class)]
class EventUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    private ?User $owner = null;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'users', cascade: ['persist'])]
    private Event $event;

    #[ORM\Column(nullable: true)]
    private null|bool $hasAccepted = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function isHasAccepted(): ?bool
    {
        return $this->hasAccepted;
    }

    public function setHasAccepted(?bool $hasAccepted): self
    {
        $this->hasAccepted = $hasAccepted;

        return $this;
    }
}
