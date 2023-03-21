<?php

declare(strict_types = 1);

namespace App\Entity\Event;

use App\Entity\User;
use App\Repository\Event\EventParticipantRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EventParticipantRepository::class)]
class EventParticipant
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'acceptedEvents')]
    private User $owner;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'eventUsers')]
    private Event $event;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private null|EventParticipantTicket $eventUserTicket;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
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

    public function getEventUserTicket(): null|EventParticipantTicket
    {
        return $this->eventUserTicket;
    }

    public function setEventUserTicket(null|EventParticipantTicket $eventUserTicket): self
    {
        $this->eventUserTicket = $eventUserTicket;

        return $this;
    }
}
