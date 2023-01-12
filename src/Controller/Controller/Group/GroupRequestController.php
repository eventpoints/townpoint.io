<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Group;

use App\Entity\Group\Group;
use App\Entity\Group\GroupRequest;
use App\Factory\Group\GroupRequestFactory;
use App\Factory\Group\GroupUserFactory;
use App\Repository\Group\GroupRepository;
use App\Repository\Group\GroupRequestRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/group')]
class GroupRequestController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly GroupRequestFactory $groupRequestFactory,
        private readonly GroupUserFactory $groupUserFactory,
        private readonly GroupRequestRepository $groupRequestRepository,
        private readonly GroupRepository $groupRepository
    ) {
    }

    #[Route(path: '/request/{id}', name: 'group_request')]
    public function request(Group $group): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $hasUserRequested = $group->hasUserRequested($currentUser);

        if ($hasUserRequested) {
            $this->addFlash(FlashValueObject::TYPE_ERROR, 'already requested');

            return $this->redirectToRoute('show_group', [
                'id' => $group->getId(),
            ]);
        }

        $eventRequest = $this->groupRequestFactory->create($group, $currentUser);
        $this->groupRequestRepository->save($eventRequest, true);

        return $this->redirectToRoute('show_group', [
            'id' => $group->getId(),
        ]);
    }

    #[Route(path: '/request/reject/{id}', name: 'reject_group_request')]
    public function reject(GroupRequest $groupRequest): Response
    {
        $groupRequest->setIsAccepted(false);
        $groupRequest->setResponseAt(new DateTimeImmutable());
        $this->groupRequestRepository->save($groupRequest, true);

        return $this->redirectToRoute('show_event', [
            'id' => $groupRequest->getGroup()
                ->getId(),
        ]);
    }

    #[Route(path: '/request/accept/{id}', name: 'accept_group_request')]
    public function accept(GroupRequest $groupRequest): Response
    {
        $groupRequest->setIsAccepted(true);
        $groupRequest->setResponseAt(new DateTimeImmutable());
        $this->groupRequestRepository->save($groupRequest, true);

        $eventUser = $this->groupUserFactory->create($groupRequest->getOwner(), $groupRequest->getGroup());
        $groupRequest->getGroup()
            ->addGroupUser($eventUser);
        $this->groupRepository->save($groupRequest->getGroup(), true);

        return $this->redirectToRoute('show_group', [
            'id' => $groupRequest->getGroup()
                ->getId(),
        ]);
    }
}
