<?php

declare(strict_types=1);

namespace App\Controller\Controller;

use App\DataTransferObjects\EventFilterDto;
use App\Form\Filter\EventFilterForm;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedController extends AbstractController
{
    public function __construct(
        private readonly EventRepository $eventRepository
    )
    {
    }

    #[Route(path: '/feed', name: 'feed')]
    public function index(Request $request): Response
    {
        return $this->render('user/feed.html.twig');
    }
}
