<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Dashboard;

use App\Repository\Event\EventRepository;
use App\Repository\ProjectRepository;
use App\Service\CurrentUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/dashboard')]
class DashboardController extends AbstractController
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly EventRepository $eventRepository,
        private readonly CurrentUserService $currentUserService
    ) {
    }

    #[Route(path: '/', name: 'dashboard')]
    public function dashboard(): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $spotlightProjects = $this->projectRepository->findProjectsByUser($currentUser);
        $starlightProjects = $this->projectRepository->findProjectsByUser($currentUser, 'STAR_LIGHT');
        $acceptedEvents = $this->eventRepository->findAcceptedEventsByUser($currentUser);

        return $this->render('user/dashboard.html.twig', [
            'acceptedEvents' => $acceptedEvents,
            'spotlightProjects' => $spotlightProjects,
            'starlightProjects' => $starlightProjects,
        ]);
    }
}
