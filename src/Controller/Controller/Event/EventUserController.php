<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Event;

use App\Entity\Event\EventUser;
use App\Repository\Event\EventUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route(path: '/event/participant')]
class EventUserController extends AbstractController
{
    public function __construct(
        private readonly EventUserRepository $eventUserRepository,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    #[Route(path: '/show/{id}', name: 'show_event_participant')]
    public function show(EventUser $eventUser): Response
    {
        return $this->render('event/participant/show.html.twig', [
            'eventUser' => $eventUser,
        ]);
    }

    #[Route(path: '/validate/{token}', name: 'event_qr_validate')]
    public function validateTicket(string $token): Response
    {
        $eventUser = $this->eventUserRepository->findOneBy([
            'token' => $token,
        ]);

        if (! $eventUser instanceof EventUser) {
            return $this->render('event/ticket/invalid.html.twig');
        }

        return $this->render('event/ticket/valid.html.twig', [
            'eventUser' => $eventUser,
        ]);
    }

    #[Route(path: '/invalidate/{id}', name: 'event_ticket_mark_used')]
    public function invalidateTicket(): Response
    {
    }
}
