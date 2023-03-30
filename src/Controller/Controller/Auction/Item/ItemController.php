<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Auction\Item;

use App\DataTransferObjects\ItemFilterDto;
use App\Entity\Auction\Item;
use App\Enum\AuctionWorkflowEnum;
use App\Form\Filter\ItemFilterForm;
use App\Form\ItemFormType;
use App\Repository\CommentRepository;
use App\Repository\ItemRepository;
use App\Repository\OfferRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route(path: '/auction')]
class ItemController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly ItemRepository $itemRepository,
        private readonly CommentRepository $commentRepository,
        private readonly OfferRepository $offerRepository,
        private readonly WorkflowInterface $auctionStateMachine,
        private readonly PaginatorInterface $paginator
    ) {
    }


    #[Route(path: '/', name: 'auction_index')]
    public function index(Request $request): Response
    {
        $marketItemFilterDto = new ItemFilterDto();
        $itemsQuery = $this->itemRepository->findByFilter($marketItemFilterDto, true);
        $marketItemPagination = $this->paginator->paginate($itemsQuery, $request->query->getInt('items-page', 1), 30, [
            'pageParameterName' => 'items-page',
        ]);

        $itemFilterForm = $this->createForm(ItemFilterForm::class, $marketItemFilterDto);
        $itemFilterForm->handleRequest($request);
        if ($itemFilterForm->isSubmitted() && $itemFilterForm->isValid()) {
            $itemsQuery = $this->itemRepository->findByFilter($marketItemFilterDto, true);
            $marketItemPagination = $this->paginator->paginate(
                $itemsQuery,
                $request->query->getInt('items-page', 1),
                30, ['pageParameterName' => 'items-page',]
            );

            return $this->render('auction/item/index.html.twig', [
                'itemFilterForm' => $itemFilterForm,
                'itemPagination' => $marketItemPagination,
            ]);
        }

        return $this->render('auction/item/index.html.twig', [
            'itemFilterForm' => $itemFilterForm,
            'itemPagination' => $marketItemPagination,
        ]);
    }

    #[Route(path: '/create', name: 'create_auction')]
    public function create(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $item = new Item();

        $item->setOwner($currentUser);
        $itemForm = $this->createForm(ItemFormType::class, $item);

        $itemForm->handleRequest($request);
        if ($itemForm->isSubmitted() && $itemForm->isValid()) {
            $this->auctionStateMachine->apply($item, AuctionWorkflowEnum::STATE_DRAFT->value);
            $this->itemRepository->save($itemForm->getData(), true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'item added');

            return $this->redirectToRoute('show_auction', [
                'id' => $item->getId(),
            ]);
        }

        return $this->render('auction/item/new.html.twig', [
            'item' => $item,
            'itemForm' => $itemForm->createView(),
        ]);
    }

    #[Route(path: '/show/{id}', name: 'show_auction')]
    public function show(Item $item, Request $request): Response
    {
        $commentsQuery = $this->commentRepository->findByMarketItem($item, true);
        $offersQuery = $this->offerRepository->findByItem($item, false);
        $marketItemCommentsPagination = $this->paginator->paginate(
            $commentsQuery,
            $request->query->getInt('item-comments-page', 1),
            30,
            ['pageParameterName' => 'items-page']
        );

        return $this->render('auction/item/show.html.twig', [
            'item' => $item,
            'offers' => $offersQuery,
            'marketItemCommentsPagination' => $marketItemCommentsPagination,
        ]);
    }
}
