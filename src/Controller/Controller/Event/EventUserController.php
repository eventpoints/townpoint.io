<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Event;

use App\Entity\Event\EventUser;
use App\Repository\Event\EventUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/event/participant')]
class EventUserController extends AbstractController
{
    public function __construct(
        private readonly EventUserRepository $eventUserRepository,
    ) {
    }

    #[Route(path: '/show/{id}', name: 'show_event_participant')]
    public function show(EventUser $eventUser): Response
    {
        return $this->render('event/participant/show.html.twig', [
            'eventUser' => $eventUser,
        ]);
    }
}
