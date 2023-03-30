<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Subscription;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/subscription')]
class SubscriptionController extends AbstractController
{
    #[Route(path: '/', name: 'create_subscription')]
    public function form(Request $request): Response
    {


        return $this->render('subscription/new.html.twig');
    }
}
