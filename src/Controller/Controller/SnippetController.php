<?php

declare(strict_types = 1);

namespace App\Controller\Controller;

use App\Entity\Snippet;
use App\Form\SnippetFormType;
use App\Service\CurrentUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/snippet', name: '')]
class SnippetController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService
    ) {
    }

    #[Route(path: '/create', name: 'new_snippet')]
    public function create(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $snippet = new Snippet();
        $snippet->setOwner($currentUser);
        $snippetForm = $this->createForm(SnippetFormType::class, $snippet);

        $snippetForm->handleRequest($request);
        if ($snippetForm->isSubmitted() && $snippetForm->isValid()) {
            $file = $snippetForm->get('content')
                ->getData();
            dd($file);
        }

        return $this->render('snippet/new.html.twig', [
            'snippetForm' => $snippetForm->createView(),
        ]);
    }
}
