<?php

declare(strict_types = 1);

namespace App\Entity\Event;

use App\Entity\User;
use App\Repository\Event\EventUserRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: EventUserRepository::class)]
class EventUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\ManyToOne(cascade: ['persist'])]
    private User $owner;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'eventUsers')]
    private Event $event;

    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $token;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private EventUserTicket $eventUserTicket;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->token = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
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

    public function getToken(): UuidV4|Uuid
    {
        return $this->token;
    }

    public function setToken(UuidV4|Uuid $token): void
    {
        $this->token = $token;
    }

    public function getEventUserTicket(): EventUserTicket
    {
        return $this->eventUserTicket;
    }

    public function setEventUserTicket(EventUserTicket $eventUserTicket): self
    {
        $this->eventUserTicket = $eventUserTicket;

        return $this;
    }
}
