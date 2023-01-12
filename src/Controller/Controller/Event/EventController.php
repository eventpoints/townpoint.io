<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Event;

use App\DataTransferObjects\EventFilterDto;
use App\Entity\Event\Event;
use App\Factory\Event\EventInviteFactory;
use App\Factory\Event\EventUserFactory;
use App\Form\EventFormType;
use App\Form\Filter\EventFilterForm;
use App\Repository\Event\EventInviteRepository;
use App\Repository\Event\EventRepository;
use App\Repository\Event\EventRequestRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/events')]
class EventController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly EventRepository $eventRepository,
        private readonly EventInviteRepository $eventInviteRepository,
        private readonly EventRequestRepository $eventRequestRepository,
        private readonly EventUserFactory $eventUserFactory,
        private readonly EventInviteFactory $eventInviteFactory,
    ) {
    }

    #[Route(path: '/', name: 'events')]
    public function index(Request $request): Response
    {
        $events = $this->eventRepository->findAll();

        $eventFilterDto = new EventFilterDto();
        $eventForm = $this->createForm(EventFilterForm::class, $eventFilterDto);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $events = $this->eventRepository->findByEventFilter($eventFilterDto);

            return $this->render('events/index.html.twig', [
                'events' => $events,
                'eventForm' => $eventForm->createView(),
            ]);
        }

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'eventForm' => $eventForm->createView(),
        ]);
    }

    #[Route(path: '/create', name: 'create_event')]
    public function create(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $event = new Event();
        $event->setOwner($currentUser);

        $participant = $this->eventUserFactory->create($currentUser, $event);
        $event->addUser($participant);
        $eventForm = $this->createForm(EventFormType::class, $event);
        $eventForm->handleRequest($request);
        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $invitations = $eventForm->get('invitations')
                ->getData();
            foreach ($invitations as $user) {
                $invite = $this->eventInviteFactory->create($event, $user);
                $this->eventInviteRepository->save($invite);
            }

            $this->eventRepository->save($eventForm->getData(), true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'event created');

            return $this->redirectToRoute('feed');
        }

        return $this->render('event/new.html.twig', [
            'eventForm' => $eventForm->createView(),
        ]);
    }

    #[Route(path: '/show/{id}', name: 'show_event')]
    public function show(Event $event, Request $request): Response
    {
        $eventRequests = $this->eventRequestRepository->findByEvent($event);

        return $this->render('event/show.html.twig', [
            'event' => $event,
            'eventRequests' => $eventRequests,
        ]);
    }
}
