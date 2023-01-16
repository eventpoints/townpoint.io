<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Event;

use App\Entity\Event\EventUserTicket;
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
class EventUserTicketController extends AbstractController
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    #[Route(path: '/show/{id}', name: 'show_event_user_ticket')]
    public function show(EventUserTicket $eventUserTicket): Response
    {
        $url = $this->urlGenerator->generate('event_qr_validate', [
            'token' => $eventUserTicket->getEventUser()
                ->getToken(),
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
    public function create(EventUserTicket $eventTicket): Response
    {
        return $this->render('');
    }
}
