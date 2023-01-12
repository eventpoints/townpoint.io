<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Comment;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/comment')]
class CommentController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly CommentRepository $commentRepository,
    ) {
    }

    #[Route(path: '/create', name: 'create_comment')]
    public function create(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $comment = new Comment();
        $comment->setOwner($currentUser);
        $commentForm = $this->createForm(CommentFormType::class, $comment);

        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->commentRepository->save($commentForm->getData(), true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'changes saved');

            return $this->redirectToRoute('show_comment', [
                'id' => $comment->getId(),
            ]);
        }

        return $this->render('comment/new.html.twig', [
            'commentForm' => $commentForm->createView(),
        ]);
    }

    #[Route(path: '/show/{id}', name: 'show_comment')]
    public function show(Comment $comment): void
    {
        $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }
}
