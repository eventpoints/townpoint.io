<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Snippet;

use App\Entity\Snippet;
use App\Form\SnippetFormType;
use App\Repository\SnippetRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/snippet', name: '')]
class SnippetController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly SnippetRepository $snippetRepository
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
            $this->snippetRepository->add($snippetForm->getData(), true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'snippet created');

            return $this->redirectToRoute('new_snippet');
        }

        return $this->render('snippet/new.html.twig', [
            'snippetForm' => $snippetForm->createView(),
        ]);
    }

    #[Route(path: '/show/{id}', name: 'show_snippet')]
    public function show(Snippet $snippet): Response
    {
        return $this->render('snippet/show.html.twig', [
            'snippet' => $snippet,
        ]);
    }
}
