<?php

declare(strict_types=1);

namespace App\Controller\Controller\Group;

use App\DataTransferObjects\GroupFilterDto;
use App\Entity\Group\Group;
use App\Factory\Group\GroupUserFactory;
use App\Form\Filter\GroupFilterForm;
use App\Form\GroupFormType;
use App\Form\Settings\GroupSettingsFromType;
use App\Repository\CommentRepository;
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

#[Route(path: '/group')]
class GroupController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly GroupUserFactory   $groupUserFactory,
        private readonly AvatarService      $avatarService,
        private readonly GroupRepository    $groupRepository,
        private readonly PaginatorInterface $paginator,
        private readonly CommentRepository  $commentRepository,
        private readonly GroupEventRepository  $groupEventRepository,
    )
    {
    }

    #[Route(path: '/', name: 'groups')]
    public function index(Request $request): Response
    {
        $groupFilterDto = new GroupFilterDto();
        $groupsQuery = $this->groupRepository->findByGroupFilter($groupFilterDto, true);
        $pagination = $this->paginator->paginate($groupsQuery, $request->query->getInt('page', 1), 30);

        $groupFilterDto = new GroupFilterDto();
        $groupForm = $this->createForm(GroupFilterForm::class, $groupFilterDto);

        $groupForm->handleRequest($request);
        if ($groupForm->isSubmitted() && $groupForm->isValid()) {
            $groupsQuery = $this->groupRepository->findByGroupFilter($groupFilterDto, true);
            $pagination = $this->paginator->paginate($groupsQuery, $request->query->getInt('page', 1), 30);

            return $this->render('group/index.html.twig', [
                'pagination' => $pagination,
                'groupForm' => $groupForm->createView(),
            ]);
        }

        return $this->render('group/index.html.twig', [
            'pagination' => $pagination,
            'groupForm' => $groupForm->createView(),
        ]);
    }

    #[Route(path: '/create', name: 'create_group')]
    public function create(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $group = new Group();
        $group->setOwner($currentUser);
        $admin = $this->groupUserFactory->createdGroupAdmin($currentUser, $group);
        $group->addGroupUser($admin);
        $groupForm = $this->createForm(GroupFormType::class, $group);

        $groupForm->handleRequest($request);
        if ($groupForm->isSubmitted() && $groupForm->isValid()) {
            $photo = $this->avatarService->createAvatar($group->getTitle());
            $group->setPhoto($photo);
            $this->groupRepository->save($group, true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'group created');

            return $this->redirectToRoute('show_group', [
                'id' => $group->getId(),
            ]);
        }

        return $this->render('group/new.html.twig', [
            'groupForm' => $groupForm->createView(),
        ]);
    }

    #[Route(path: '/show/{id}', name: 'show_group')]
    public function show(Group $group, Request $request): Response
    {
        $commentQuery = $this->commentRepository->findByGroup(group: $group, isQuery: true);
        $groupCommentPagination = $this->paginator->paginate($commentQuery, $request->query->getInt('group-comment-page', 1), 10, [
            'pageParameterName' => 'group-comment-page'
        ]);

        $groupEventQuery = $this->groupEventRepository->findByGroup(group: $group, isQuery: true);
        $groupEventPagination = $this->paginator->paginate($groupEventQuery, $request->query->getInt('group-event-page', 1), 10, [
            'pageParameterName' => 'group-event-page'
        ]);

        $groupUrl = $this->generateUrl('show_group', [
            'id' => $group->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->render('group/show.html.twig', [
            'group' => $group,
            'groupCommentPagination' => $groupCommentPagination,
            'groupEventPagination' => $groupEventPagination,
            'groupUrl' => $groupUrl
        ]);
    }

    #[Route(path: '/edit/{id}', name: 'edit_group')]
    public function edit(Group $group): Response
    {
        return $this->render('group/show.html.twig', [
            'group' => $group,
        ]);
    }

    #[Route(path: '/settings/{id}', name: 'settings_group')]
    public function settings(Group $group, Request $request): Response
    {
        $groupForm = $this->createForm(GroupSettingsFromType::class, $group);
        $groupForm->handleRequest($request);

        if ($groupForm->isSubmitted() && $groupForm->isValid()) {
            $this->groupRepository->save($groupForm->getData(), true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'changes saved');
            return $this->redirectToRoute('show_group', ['id' => $group->getId()]);
        }

        return $this->render('group/settings.html.twig', [
            'group' => $group,
            'groupForm' => $groupForm->createView(),
        ]);
    }
}
