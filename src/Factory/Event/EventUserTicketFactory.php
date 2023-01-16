<?php

declare(strict_types = 1);

namespace App\Factory\Event;

use App\Entity\Event\EventUser;
use App\Entity\Event\EventUserTicket;
use App\Entity\Ticket\Ticket;
use App\Factory\Ticket\TicketFactory;

class EventUserTicketFactory
{
    public function __construct(
        private readonly TicketFactory $ticketFactory
    ) {
    }

    public function create(EventUser $eventUser, Ticket $ticket): EventUserTicket
    {
        $eventTicket = new EventUserTicket();
        $eventTicket->setEventUser($eventUser);
        $eventTicket->setTicket($ticket);

        return $eventTicket;
    }

    public function createTicketAndEventUserTicket(EventUser $eventUser): EventUserTicket
    {
        $ticket = $this->ticketFactory->create($eventUser->getOwner());

        return $this->create($eventUser, $ticket);
    }
}
