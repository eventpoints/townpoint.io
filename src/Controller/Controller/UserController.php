<?php

namespace App\Controller\Controller;

use App\Entity\User;
use App\Form\Form\AvatarFormType;
use App\Form\Form\UserAccountFormType;
use App\Repository\UserRepository;
use App\Service\AvatarService\Contract\AvatarServiceInterface;
use App\Service\ImageUploadService\AvatarUploadService;
use App\Service\ImageUploadService\Contract\ImageUploadServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        #[Autowire(service: AvatarUploadService::class)]
        private readonly ImageUploadServiceInterface $avatarUploadService

    ) {
    }

    #[Route(path: '/account', name: 'user_account')]
    public function account(#[CurrentUser] User $currentUser, Request $request): Response
    {
        $userAccountForm = $this->createForm(UserAccountFormType::class, $currentUser);
        $userAccountForm->handleRequest($request);
        if ($userAccountForm->isSubmitted() && $userAccountForm->isValid()) {
            $this->userRepository->save($currentUser, true);
            return $this->redirectToRoute('user_profile', [
                'handle' => $currentUser->getHandle(),
            ]);
        }

        return $this->render('user/account.html.twig', [
            'userAccountForm' => $userAccountForm->createView(),
            'user' => $currentUser,
        ]);
    }

    #[Route(path: '/search/users', name: 'user_search')]
    public function search(Request $request): Response
    {
        $keyword = $request->get('keyword');
        $users = $this->userRepository->findByHandle($keyword);

        return $this->render('user/search.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route(path: '/change/avatar', name: 'change_avatar')]
    public function changeProfileAvatar(
        Request $request,
        #[CurrentUser] User $currentUser
    ) : Response
    {
        $avatarForm = $this->createForm(AvatarFormType::class);
        $avatarForm->handleRequest($request);
        if ($avatarForm->isSubmitted() && $avatarForm->isValid()) {

            $image = $avatarForm->get('avatar')->getData();
            $avatar = $this->avatarUploadService->process($image);
            $currentUser->setAvatar($avatar->getEncoded());
            $this->userRepository->save($currentUser, true);

            return $this->redirectToRoute('user_account', [
                'handle' => $currentUser->getHandle(),
            ]);
        }

        return $this->render('user/change-avatar.html.twig', [
            'avatarForm' => $avatarForm
        ]);
    }
    
}
