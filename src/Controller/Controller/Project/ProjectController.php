<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Project;

use App\Entity\Project;
use App\Form\ProjectFormType;
use App\Repository\ProjectRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/project')]
class ProjectController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    #[Route(path: '/create', name: 'create_project')]
    public function create(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $project = new Project();
        $project->setOwner($currentUser);
        $projectForm = $this->createForm(ProjectFormType::class, $project);

        $projectForm->handleRequest($request);
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $this->projectRepository->save($project, true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'project created');

            return $this->redirectToRoute($request->get('_route'));
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'projectForm' => $projectForm->createView(),
        ]);
    }
}
