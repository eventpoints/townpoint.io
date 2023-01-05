<?php

namespace App\Controller\Controller;

use App\DataTransferObjects\EventFilterDto;
use App\Entity\Address;
use App\Entity\Event;
use App\Entity\EventRequest;
use App\Entity\EventUser;
use App\Factory\EventRequestFactory;
use App\Factory\EventUserFactory;
use App\Form\AddressFormType;
use App\Form\EventFormType;
use App\Form\Filter\EventFilterForm;
use App\Repository\AddressRepository;
use App\Repository\EventRepository;
use App\Repository\EventRequestRepository;
use App\Repository\MessageRepository;
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
        private readonly EventRepository    $eventRepository,
        private readonly EventRequestRepository    $eventRequestRepository,
        private readonly EventUserFactory   $eventUserFactory,
        private readonly EventRequestFactory  $eventRequestFactory,
    )
    {
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
                'eventForm' => $eventForm->createView()
            ]);
        }

        return $this->render('events/index.html.twig', [
            'events' => $events,
            'eventForm' => $eventForm->createView()
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
            'eventRequests' => $eventRequests
        ]);
    }


    #[Route(path: '/request/{id}', name: 'create_event_request')]
    public function request(Event $event): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $eventRequest = $this->eventRequestFactory->create($event, $currentUser);
        $this->eventRequestRepository->save($eventRequest, true);

        return $this->redirectToRoute('show_event', ['id' => $event->getId()]);
    }


    #[Route(path: '/request/reject/{id}', name: 'reject_event_request')]
    public function reject(EventRequest $eventRequest): Response
    {
        $eventRequest->setIsAccepted(false);
        $this->eventRequestRepository->save($eventRequest, true);
        return $this->redirectToRoute('show_event', ['id' => $eventRequest->getEvent()->getId()]);
    }

    #[Route(path: '/request/accept/{id}', name: 'accept_event_request')]
    public function accept(EventRequest $eventRequest): Response
    {
        $eventRequest->setIsAccepted(true);
        $this->eventRequestRepository->save($eventRequest, true);

        $eventUser = $this->eventUserFactory->create($eventRequest->getOwner(), $eventRequest->getEvent());
        $eventRequest->getEvent()->addUser($eventUser);
        $this->eventRepository->save($eventRequest->getEvent(), true);

        return $this->redirectToRoute('show_event', ['id' => $eventRequest->getEvent()->getId()]);
    }


}