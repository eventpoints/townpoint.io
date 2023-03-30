<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Auction\Item\Comment;

use App\Entity\Auction\Item;
use App\Entity\Comment;
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

#[Route(path: '/auction/item/comment')]
class ItemCommentController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly CommentFactory $commentFactory,
        private readonly CommentRepository $commentRepository
    ) {
    }

    #[Route(path: '/create/{id}', name: 'market_item_create_comment')]
    public function create(Item $item, Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $comment = $this->commentFactory->createMarketItem($currentUser, '', $item);
        $commentForm = $this->createForm(CommentFormType::class, $comment);

        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->commentRepository->save($commentForm->getData(), true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'changes saved');

            return $this->redirectToRoute('show_market_item', [
                'id' => $item->getId(),
            ]);
        }

        return $this->render('comment/new.html.twig', [
            'commentForm' => $commentForm->createView(),
            'path' => $this->generateUrl('market_item_create_comment', [
                'id' => $item->getId(),
            ]),
        ]);
    }

    #[Route(path: '/delete/{id}', name: 'market_comment_delete')]
    public function remove(Comment $comment, Request $request): Response
    {
        $marketItem = $comment->getMarketItem();

        if (! $marketItem instanceof Item) {
            throw new ShouldNotHappenException('Item object required');
        }

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), (string)$request->request->get('_token'))) {
            $this->commentRepository->remove($comment, true);
        }

        return $this->redirectToRoute('show_market_item', [
            'id' => $marketItem->getId(),
        ], Response::HTTP_SEE_OTHER);
    }
}
