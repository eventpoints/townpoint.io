<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Event;

use App\Entity\Event\Event;
use App\Entity\Event\EventRequest;
use App\Factory\Event\EventRejectionFactory;
use App\Factory\Event\EventRequestFactory;
use App\Factory\Event\EventUserFactory;
use App\Factory\Event\EventUserTicketFactory;
use App\Repository\Event\EventRejectionRepository;
use App\Repository\Event\EventRepository;
use App\Repository\Event\EventRequestRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/events')]
class EventRequestController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly EventRepository $eventRepository,
        private readonly EventRequestRepository $eventRequestRepository,
        private readonly EventUserFactory $eventUserFactory,
        private readonly EventRequestFactory $eventRequestFactory,
        private readonly EventUserTicketFactory $eventTicketFactory,
        private readonly EventRejectionFactory $eventRejectionFactory,
        private readonly EventRejectionRepository $eventRejectionRepository,
    ) {
    }

    #[Route(path: '/request/{id}', name: 'create_event_request')]
    public function request(Event $event): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $hasUserRequested = $event->hasUserRequested($currentUser);

        if ($hasUserRequested) {
            $this->addFlash(FlashValueObject::TYPE_ERROR, 'already requested');

            return $this->redirectToRoute('show_event', [
                'id' => $event->getId(),
            ]);
        }

        $eventRequest = $this->eventRequestFactory->create($event, $currentUser);
        $this->eventRequestRepository->save($eventRequest, true);

        return $this->redirectToRoute('show_event', [
            'id' => $event->getId(),
        ]);
    }

    #[Route(path: '/request/reject/{id}', name: 'reject_event_request')]
    public function reject(EventRequest $eventRequest): Response
    {
        $eventRejection = $this->eventRejectionFactory->create($eventRequest->getEvent(), $eventRequest->getOwner());
        $this->eventRejectionRepository->save($eventRejection, true);
        $this->eventRequestRepository->remove($eventRequest, true);

        return $this->redirectToRoute('show_event', [
            'id' => $eventRequest->getEvent()
                ->getId(),
        ]);
    }

    #[Route(path: '/request/accept/{id}', name: 'accept_event_request')]
    public function accept(EventRequest $eventRequest): Response
    {
        $eventUser = $this->eventUserFactory->create($eventRequest->getOwner(), $eventRequest->getEvent());
        $eventRequest->getEvent()
            ->addEventUser($eventUser);

        if ($eventRequest->getEvent()->isIsTicketed()) {
            $eventUserTicket = $this->eventTicketFactory->createTicketAndEventUserTicket($eventUser);
            $eventUser->setEventUserTicket($eventUserTicket);
        }

        $this->eventRepository->save($eventRequest->getEvent(), true);
        $this->eventRequestRepository->remove($eventRequest, true);

        return $this->redirectToRoute('show_event', [
            'id' => $eventRequest->getEvent()
                ->getId(),
        ]);
    }
}
