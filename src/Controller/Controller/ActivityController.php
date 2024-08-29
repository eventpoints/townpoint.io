<?php

namespace App\Controller\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{


    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
    }

    #[Route(path: '/active/users', name: 'get_active_users')]
    public function getActiveUsers() : Response
    {
        $users = $this->userRepository->findActiveWithin20Minutes();
        return $this->render('partial/_active-users.html.twig', [
            'users' => $users
        ]);
    }

}