<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Event;

use App\DataTransferObjects\EventFilterDto;
use App\Entity\Event\Event;
use App\Factory\Event\EventInviteFactory;
use App\Factory\Event\EventUserFactory;
use App\Factory\Event\EventUserTicketFactory;
use App\Form\EventFormType;
use App\Form\Filter\EventFilterForm;
use App\Repository\CommentRepository;
use App\Repository\Event\EventInviteRepository;
use App\Repository\Event\EventRejectionRepository;
use App\Repository\Event\EventRepository;
use App\Repository\Event\EventRequestRepository;
use App\Repository\Event\EventUserRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Knp\Component\Pager\PaginatorInterface;
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
        private readonly EventRejectionRepository $eventRejectionRepository,
        private readonly EventRequestRepository $eventRequestRepository,
        private readonly EventUserFactory $eventUserFactory,
        private readonly EventUserRepository $eventUserRepository,
        private readonly EventInviteFactory $eventInviteFactory,
        private readonly PaginatorInterface $paginator,
        private readonly EventUserTicketFactory $eventUserTicketFactory,
        private readonly CommentRepository $commentRepository
    ) {
    }

    #[Route(path: '/', name: 'events')]
    public function index(Request $request): Response
    {
        $eventFilterDto = new EventFilterDto();
        $eventQuery = $this->eventRepository->findByEventFilter($eventFilterDto, true);
        $pagination = $this->paginator->paginate($eventQuery, $request->query->getInt('page', 1), 30);

        $eventForm = $this->createForm(EventFilterForm::class, $eventFilterDto);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $eventQuery = $this->eventRepository->findByEventFilter($eventFilterDto, true);
            $pagination = $this->paginator->paginate($eventQuery, $request->query->getInt('page', 1), 30);

            return $this->render('event/index.html.twig', [
                'pagination' => $pagination,
                'eventForm' => $eventForm->createView(),
            ]);
        }

        return $this->render('event/index.html.twig', [
            'pagination' => $pagination,
            'eventForm' => $eventForm->createView(),
        ]);
    }

    #[Route(path: '/create', name: 'create_event')]
    public function create(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $event = new Event();
        $event->setOwner($currentUser);
        $eventUser = $this->eventUserFactory->create($currentUser, $event);
        $event->addEventUser($eventUser);

        $eventForm = $this->createForm(EventFormType::class, $event);
        $eventForm->handleRequest($request);
        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $invitations = $eventForm->get('invitations')
                ->getData();

            foreach ($invitations as $user) {
                if ($user !== $currentUser) {
                    $invite = $this->eventInviteFactory->create($event, $user);
                    $this->eventInviteRepository->save($invite);
                }
            }

            if ($event->isIsTicketed()) {
                $eventUserTicket = $this->eventUserTicketFactory->createTicketAndEventUserTicket($eventUser);
                $eventUser->setEventUserTicket($eventUserTicket);
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
        $eventParticipantsQuery = $this->eventUserRepository->findByEvent($event, true);
        $eventParticipantsPagination = $this->paginator->paginate(
            $eventParticipantsQuery,
            $request->query->getInt('event-participants-page', 1),
            10,
            [
                'pageParameterName' => 'event-participants-page',
            ]
        );

        $eventInvitationsQuery = $this->eventInviteRepository->findByEvent($event, true);
        $eventInvitationsPagination = $this->paginator->paginate(
            $eventInvitationsQuery,
            $request->query->getInt('event-invitations-page', 1),
            10,
            [
                'pageParameterName' => 'event-invitations-page',
            ]
        );

        $eventRequestsQuery = $this->eventRequestRepository->findByEvent($event, true);
        $eventRequestsPagination = $this->paginator->paginate(
            $eventRequestsQuery,
            $request->query->getInt('event-requests-page', 1),
            10,
            [
                'pageParameterName' => 'event-requests-page',
            ]
        );

        $eventRejectionsQuery = $this->eventRejectionRepository->findByEvent($event, true);
        $eventRejectionsPagination = $this->paginator->paginate(
            $eventRejectionsQuery,
            $request->query->getInt('event-rejections-page', 1),
            10,
            [
                'pageParameterName' => 'event-rejections-page',
            ]
        );

        $commentQuery = $this->commentRepository->findByEvent(event: $event, isQuery: true);
        $eventCommentPagination = $this->paginator->paginate(
            $commentQuery,
            $request->query->getInt('event-comment-page', 1),
            10,
            [
                'pageParameterName' => 'event-comment-page',
            ]
        );

        return $this->render('event/show.html.twig', [
            'event' => $event,
            'eventParticipantsPagination' => $eventParticipantsPagination,
            'eventInvitationsPagination' => $eventInvitationsPagination,
            'eventRejectionsPagination' => $eventRejectionsPagination,
            'eventRequestsPagination' => $eventRequestsPagination,
            'eventCommentPagination' => $eventCommentPagination,
        ]);
    }
}
