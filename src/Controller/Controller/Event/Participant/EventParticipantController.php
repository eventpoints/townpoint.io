<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Event\Participant;

use App\Entity\Event\EventParticipant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/event/participant')]
class EventParticipantController extends AbstractController
{
    #[Route(path: '/show/{id}', name: 'show_event_participant')]
    public function show(EventParticipant $eventUser): Response
    {
        return $this->render('event/participant/show.html.twig', [
            'eventUser' => $eventUser,
        ]);
    }
}
