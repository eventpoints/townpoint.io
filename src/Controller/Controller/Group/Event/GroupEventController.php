<?php

declare(strict_types=1);

namespace App\Controller\Controller\Group\Event;

use App\DataTransferObjects\GroupFilterDto;
use App\Entity\Event\Event;
use App\Entity\Group\Group;
use App\Entity\Group\GroupEvent;
use App\Factory\Event\EventInviteFactory;
use App\Factory\Event\EventUserFactory;
use App\Factory\Event\EventUserTicketFactory;
use App\Factory\Group\Event\GroupEventFactory;
use App\Factory\Group\GroupUserFactory;
use App\Form\EventFormType;
use App\Form\Filter\GroupFilterForm;
use App\Form\GroupEventFormType;
use App\Form\GroupFormType;
use App\Form\Settings\GroupSettingsFromType;
use App\Repository\Event\EventInviteRepository;
use App\Repository\Event\EventRepository;
use App\Repository\Group\GroupEventRepository;
use App\Repository\Group\GroupRepository;
use App\Service\AvatarService;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route(path: '/group/event')]
class GroupEventController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService     $currentUserService,
        private readonly GroupEventFactory      $groupEventFactory,
        private readonly EventUserFactory       $eventUserFactory,
        private readonly EventInviteFactory     $eventInviteFactory,
        private readonly EventInviteRepository  $eventInviteRepository,
        private readonly EventUserTicketFactory $eventUserTicketFactory,
        private readonly EventRepository        $eventRepository,
        private readonly GroupEventRepository   $groupEventRepository
    )
    {
    }

    #[Route(path: '/create/{id}', name: 'create_group_event')]
    public function create(Group $group, Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $event = new Event();
        $event->setOwner($currentUser);
        $eventUser = $this->eventUserFactory->create($currentUser, $event);
        $event->addEventUser($eventUser);

        $eventForm = $this->createForm(GroupEventFormType::class, $event);
        $eventForm->handleRequest($request);
        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $groupEvent = $this->groupEventFactory->create($group, $event);
            $event->setGroupEvent($groupEvent);

            if ($eventForm->get('inviteMembers')->getData()) {
                foreach ($group->getGroupUsers() as $groupUser) {
                    if ($groupUser->getOwner() !== $currentUser) {
                        $invite = $this->eventInviteFactory->create($event, $groupUser->getOwner());
                        $this->eventInviteRepository->save($invite);
                    }
                }
            }

            if ($event->isIsTicketed()) {
                $eventUserTicket = $this->eventUserTicketFactory->createTicketAndEventUserTicket($eventUser);
                $eventUser->setEventUserTicket($eventUserTicket);
            }

            $this->eventRepository->save($event, true);
            $this->groupEventRepository->save($groupEvent, true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'group event created');

            return $this->redirectToRoute('show_group', [
                'id' => $group->getId(),
            ]);
        }

        return $this->render('group/event/new.html.twig', [
            'eventForm' => $eventForm->createView()
        ]);
    }

}
