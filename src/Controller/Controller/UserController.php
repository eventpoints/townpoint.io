<?php

declare(strict_types = 1);

namespace App\Controller\Controller;

use App\Entity\User;
use App\Form\UserAccountFormType;
use App\Repository\SnippetRepository;
use App\Repository\UserRepository;
use App\Repository\ViewRepository;
use App\Service\CurrentUserService;
use App\Service\ImageUploadService;
use App\Service\ProfileViewService;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ImageUploadService $imageUploadService,
        private readonly ProfileViewService $profileViewService,
        private readonly ViewRepository $viewRepository,
        private readonly CurrentUserService $currentUserService,
        private readonly SnippetRepository $snippetRepository,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route(path: '/insights', name: 'insights')]
    public function insight(): Response
    {
        $currentUser = $this->getUser();
        assert($currentUser instanceof User && $currentUser instanceof UserInterface);
        $views = $this->viewRepository->findByCurrentUser($currentUser);

        return $this->render('user/insights.html.twig', [
            'views' => $views,
        ]);
    }

    #[Route(path: '/dashboard', name: 'dashboard')]
    public function dashboard(): Response
    {
        return $this->render('user/dashboard.html.twig');
    }

    #[Route(path: '/account', name: 'account')]
    public function account(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $userForm = $this->createForm(UserAccountFormType::class, $currentUser);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            /** @var UploadedFile $file */
            $file = $userForm->get('avatar')
                ->getData();

            if ($file instanceof UploadedFile) {
                $base64Image = $this->imageUploadService->processAvatar($file);
                $currentUser->setAvatar($base64Image->getEncoded());
            }

            $this->userRepository->add($currentUser, true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, FlashValueObject::MESSAGE_SUCCESS_SAVED);

            return $this->redirectToRoute('account');
        }

        return $this->render('user/account.html.twig', [
            'userForm' => $userForm->createView(),
        ]);
    }

    #[Route(path: '/profile/{id}', name: 'profile')]
    public function profile(User $user, Request $request): Response
    {
        if ($this->getUser() instanceof UserInterface) {
            $this->profileViewService->view($user);
        }

        $snippets = $this->snippetRepository->findBy([
            'owner' => $this->getUser(),
        ], [
            'createdAt' => 'DESC',
        ]);

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'snippets' => $this->serializer->serialize($snippets, 'json', [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['owner'],
            ]),
        ]);
    }

    #[Route(path: '/menu/profile', name: 'profile_menu')]
    public function profileMenu(): Response
    {
        return $this->render('user/_profile-post-menu.html.twig');
    }

    #[Route(path: '/email/render', name: 'test_email_render')]
    public function email(): Response
    {
        return $this->render('email/registration.email.html.twig', [
            'user' => $this->getUser(),
            'path' => 'something',
        ]);
    }
}
