<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Group;

use App\Entity\Comment;
use App\Entity\Group\Group;
use App\Exception\ShouldNotHappenException;
use App\Factory\Comment\CommentFactory;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/group/comment')]
class GroupCommentController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly CommentFactory $commentFactory,
        private readonly CommentRepository $commentRepository
    ) {
    }

    #[Route(path: '/create/{id}', name: 'group_create_comment')]
    public function create(Group $group, Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $comment = $this->commentFactory->createGroupComment($currentUser, '', $group);
        $commentForm = $this->createForm(CommentFormType::class, $comment);

        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->commentRepository->save($commentForm->getData(), true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'changes saved');

            return $this->redirectToRoute('show_group', [
                'id' => $group->getId(),
            ]);
        }

        return $this->render('comment/new.html.twig', [
            'commentForm' => $commentForm->createView(),
            'path' => $this->generateUrl('group_create_comment', [
                'id' => $group->getId(),
            ]),
        ]);
    }

    #[Route(path: '/delete/{id}', name: 'group_comment_delete')]
    public function remove(Comment $comment, Request $request): Response
    {
        $group = $comment->getGroup();

        if (! $group instanceof Group) {
            throw new ShouldNotHappenException('Group object required');
        }

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), (string)$request->request->get('_token'))) {
            $this->commentRepository->remove($comment, true);
        }

        return $this->redirectToRoute('show_group', [
            'id' => $group->getId(),
        ], Response::HTTP_SEE_OTHER);
    }
}
