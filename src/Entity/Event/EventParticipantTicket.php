<?php

declare(strict_types = 1);

namespace App\Entity\Event;

use App\Entity\Ticket\Ticket;
use App\Repository\Ticket\EventParticipantTicketRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: EventParticipantTicketRepository::class)]
class EventParticipantTicket
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\OneToOne(mappedBy: 'eventUserTicket', targetEntity: EventParticipant::class, orphanRemoval: true)]
    private EventParticipant $eventUser;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Ticket $ticket;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $token;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->token = Uuid::v4();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEventUser(): EventParticipant
    {
        return $this->eventUser;
    }

    public function setEventUser(EventParticipant $eventUser): void
    {
        $this->eventUser = $eventUser;
    }

    public function getTicket(): Ticket
    {
        return $this->ticket;
    }

    public function setTicket(Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

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
}
