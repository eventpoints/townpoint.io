<?php

namespace App\Controller\Controller;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route(path: '/u/{handle}', name: 'user_profile')]
    public function index(
        #[MapEntity(mapping: [
            'handle' => 'handle',
        ])]
        User $user,
        Request $request
    ): Response {
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
}
