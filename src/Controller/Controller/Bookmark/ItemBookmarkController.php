<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Bookmark;

use App\Entity\Auction\Item;
use App\Entity\Bookmark;
use App\Factory\Bookmark\BookmarkFactory;
use App\Repository\BookmarkRepository;
use App\Service\CurrentUserService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'bookmark/item')]
class ItemBookmarkController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly BookmarkFactory $bookmarkFactory,
        private readonly BookmarkRepository $bookmarkRepository,
        private readonly PaginatorInterface $paginator,
    ) {
    }

    #[Route(path: '/create/{id}', name: 'create_item_bookmark')]
    public function create(Item $item, Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $bookmark = $this->bookmarkRepository->findOneBy([
            'owner' => $currentUser,
            'item' => $item,
        ]);

        if ($bookmark instanceof Bookmark) {
            $this->bookmarkRepository->remove($bookmark, true);

            return $this->redirectToRoute('auction');
        }

        $bookmark = $this->bookmarkFactory->createItemBookmark(user: $currentUser, item: $item);

        $this->bookmarkRepository->save($bookmark, true);

        return $this->redirectToRoute('auction');
    }

    #[Route(path: '/', name: 'market_item_bookmarks')]
    public function index(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $bookmarksQuery = $this->bookmarkRepository->findByUser(user: $currentUser, isQuery: true);
        $bookmarkPagination = $this->paginator->paginate(
            $bookmarksQuery,
            $request->query->getInt('bookmark-page', 1),
            30
        );

        return $this->render('bookmarks/market/items/index.html.twig', [
            'bookmarkPagination' => $bookmarkPagination,
        ]);
    }
}
