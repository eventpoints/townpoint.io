<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Comment;

use App\Entity\Event\Event;
use App\Factory\Comment\CommentFactory;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/event/comment/')]
class EventCommentController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly CommentFactory $commentFactory,
        private readonly CommentRepository $commentRepository
    ) {
    }

    #[Route(path: '/create/{id}', name: 'event_create_comment')]
    public function create(Event $event, Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $comment = $this->commentFactory->createEventComment($currentUser, '', $event);
        $commentForm = $this->createForm(CommentFormType::class, $comment);

        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->commentRepository->save($commentForm->getData(), true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'changes saved');

            return $this->redirectToRoute('show_event', [
                'id' => $event->getId(),
            ]);
        }

        return $this->render('comment/new.html.twig', [
            'commentForm' => $commentForm->createView(),
            'path' => $this->generateUrl('event_create_comment', [
                'id' => $event->getId(),
            ]),
        ]);
    }
}
