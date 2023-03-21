<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Event;

use App\Entity\Event\EventParticipantTicket;
use App\Repository\Ticket\EventParticipantTicketRepository;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route(path: '/event/ticketing')]
class EventParticipantTicketController extends AbstractController
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly EventParticipantTicketRepository $eventUserTicketRepository
    ) {
    }

    #[Route(path: '/show/{id}', name: 'show_event_user_ticket')]
    public function show(EventParticipantTicket $eventUserTicket): Response
    {
        $url = $this->urlGenerator->generate('event_qr_validate', [
            'token' => $eventUserTicket->getToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $ticketQr = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(500)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->validateResult(false)
            ->build();

        return $this->render('/event/ticket/show.html.twig', [
            'eventUserTicket' => $eventUserTicket,
            'ticketQr' => $ticketQr->getDataUri(),
        ]);
    }

    #[Route(path: '/create', name: 'new_event_ticket')]
    public function create(EventParticipantTicket $eventTicket): Response
    {
        return $this->render('');
    }

    #[Route(path: '/validate/{token}', name: 'event_qr_validate')]
    public function validateTicket(string $token): Response
    {
        $eventUserTicket = $this->eventUserTicketRepository->findOneBy([
            'token' => $token,
        ]);

        if (! $eventUserTicket instanceof EventParticipantTicket) {
            return $this->render('event/ticket/invalid.html.twig');
        }

        return $this->render('event/ticket/valid.html.twig', [
            'eventUserTicket' => $eventUserTicket,
        ]);
    }

    #[Route(path: '/invalidate/{id}', name: 'event_ticket_mark_used')]
    public function invalidateTicket(): Response
    {
        return $this->render('terms/index.html.twig');
    }
}
