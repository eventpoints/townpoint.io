<?php

declare(strict_types = 1);

namespace App\Controller\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function index(): Response
    {
        return $this->redirectToRoute('register_step_one');
    }

    #[Route(path: '/terms', name: 'app_terms')]
    public function terms(): Response
    {
        return $this->render('terms/index.html.twig');
    }
}
