<?php

namespace App\Controller\Controller;

use App\Entity\User;
use App\Repository\ProfileViewRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    public function __construct(
        private readonly ProfileViewRepository $profileViewRepository
    ) {
    }

    #[Route(path: '/u/{handle}', name: 'user_profile')]
    public function index(
        #[MapEntity(mapping: [
            'handle' => 'handle',
        ])]
        User $user,
        Request $request,
    ): Response {
        $profileViews = $this->profileViewRepository->findByTargetUser(user: $user);

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'profileViews' => $profileViews,
        ]);
    }
}
