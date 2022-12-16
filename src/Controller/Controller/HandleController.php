<?php

namespace App\Controller\Controller;

use App\Form\HandleSearchFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/handle')]
class HandleController extends AbstractController
{


    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
    }

    #[Route(path: '/', name: 'handle_index')]
    public function index() : Response
    {
        $users = $this->userRepository->findAll();
        $handleSearchForm = $this->createForm(HandleSearchFormType::class, $users);

        return $this->render('handle/index.html.twig', [
            'handleSearchForm' => $handleSearchForm->createView()
        ]);

    }

}