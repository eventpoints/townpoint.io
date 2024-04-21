<?php

namespace App\Controller\Controller;

use App\Entity\User;
use App\Form\Form\UserAccountFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository
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
}
