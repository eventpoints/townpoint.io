<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Group;

use App\Entity\Group\Group;
use App\Factory\Group\GroupUserFactory;
use App\Form\GroupFormType;
use App\Repository\Group\GroupRepository;
use App\Service\AvatarService;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/group')]
class GroupController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly GroupUserFactory $groupUserFactory,
        private readonly AvatarService $avatarService,
        private readonly GroupRepository $groupRepository,
    ) {
    }

    #[Route(path: '/', name: 'groups')]
    public function index(): Response
    {
        $groups = $this->groupRepository->findAll();

        return $this->render('group/index.html.twig', [
            'groups' => $groups,
        ]);
    }

    #[Route(path: '/create', name: 'create_group')]
    public function create(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $group = new Group();
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
    public function show(Group $group): Response
    {
        return $this->render('group/show.html.twig', [
            'group' => $group,
        ]);
    }

    #[Route(path: '/edit/{id}', name: 'edit_group')]
    public function edit(Group $group): Response
    {
        return $this->render('group/show.html.twig', [
            'group' => $group,
        ]);
    }
}
