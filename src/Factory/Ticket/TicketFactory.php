<?php

declare(strict_types = 1);

namespace App\Factory\Ticket;

use App\Entity\Ticket\Ticket;
use App\Entity\User;

class TicketFactory
{
    public function create(User $user): Ticket
    {
        $ticket = new Ticket();
        $ticket->setOwner($user);

        return $ticket;
    }
}
