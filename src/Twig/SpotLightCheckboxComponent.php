<?php

declare(strict_types = 1);

namespace App\Twig;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('spotlight_checkbox')]
class SpotLightCheckboxComponent extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public Project $project;

    public function __construct(
        private readonly ProjectRepository $projectRepository
    ) {
    }

    #[LiveAction]
    public function toggleIsComplete(): void
    {
        $this->project->setIsComplete(! $this->project->getIsComplete());
        $this->projectRepository->save($this->project, true);
    }
}
