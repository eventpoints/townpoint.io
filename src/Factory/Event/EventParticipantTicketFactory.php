<?php

declare(strict_types = 1);

namespace App\Factory\Event;

use App\Entity\Event\EventParticipant;
use App\Entity\Event\EventParticipantTicket;
use App\Entity\Ticket\Ticket;
use App\Factory\Ticket\TicketFactory;

class EventParticipantTicketFactory
{
    public function __construct(
        private readonly TicketFactory $ticketFactory
    ) {
    }

    public function create(EventParticipant $eventUser, Ticket $ticket): EventParticipantTicket
    {
        $eventTicket = new EventParticipantTicket();
        $eventTicket->setEventUser($eventUser);
        $eventTicket->setTicket($ticket);

        return $eventTicket;
    }

    public function createTicketAndEventUserTicket(EventParticipant $eventUser): EventParticipantTicket
    {
        $ticket = $this->ticketFactory->create($eventUser->getOwner());

        return $this->create($eventUser, $ticket);
    }
}
