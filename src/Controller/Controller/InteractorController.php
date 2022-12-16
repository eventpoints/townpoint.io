<?php

namespace App\Controller\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InteractorController extends AbstractController
{

    #[Route(path: '/interactors', name: 'interactors')]
    public function index(): Response
    {
        return $this->render('interactors/index.html.twig');
    }

}