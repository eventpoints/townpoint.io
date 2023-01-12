<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Event;

use App\Entity\Event\Event;
use App\Entity\Event\EventRequest;
use App\Factory\Event\EventRequestFactory;
use App\Factory\Event\EventUserFactory;
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
        $eventRequest->setIsAccepted(false);
        $this->eventRequestRepository->save($eventRequest, true);

        return $this->redirectToRoute('show_event', [
            'id' => $eventRequest->getEvent()
                ->getId(),
        ]);
    }

    #[Route(path: '/request/accept/{id}', name: 'accept_event_request')]
    public function accept(EventRequest $eventRequest): Response
    {
        $eventRequest->setIsAccepted(true);
        $this->eventRequestRepository->save($eventRequest, true);

        $eventUser = $this->eventUserFactory->create($eventRequest->getOwner(), $eventRequest->getEvent());
        $eventRequest->getEvent()
            ->addUser($eventUser);
        $this->eventRepository->save($eventRequest->getEvent(), true);

        return $this->redirectToRoute('show_event', [
            'id' => $eventRequest->getEvent()
                ->getId(),
        ]);
    }
}
