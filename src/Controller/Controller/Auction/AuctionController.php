<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Auction;

use App\DataTransferObjects\ItemFilterDto;
use App\Entity\Auction\Auction;
use App\Entity\Auction\Item;
use App\Form\AuctionFormType;
use App\Form\Filter\ItemFilterForm;
use App\Repository\ClassifiedRepository;
use App\Repository\ItemRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/auction')]
class AuctionController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly ClassifiedRepository $classifiedRepository,
        private readonly ItemRepository $itemRepository,
        private readonly PaginatorInterface $paginator,
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
                30,
                [
                    'pageParameterName' => 'items-page',
                ]
            );

            return $this->render('auction/index.html.twig', [
                'itemFilterForm' => $itemFilterForm,
                'itemPagination' => $marketItemPagination,
            ]);
        }

        return $this->render('auction/index.html.twig', [
            'itemFilterForm' => $itemFilterForm,
            'itemPagination' => $marketItemPagination,
        ]);
    }

    #[Route(path: '/create', name: 'create_auction')]
    public function create(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $auction = new Auction();

        $item = new Item();
        $auction->addItem($item);

        $auction->setOwner($currentUser);
        $classifiedForm = $this->createForm(AuctionFormType::class, $auction);

        $classifiedForm->handleRequest($request);
        if ($classifiedForm->isSubmitted() && $classifiedForm->isValid()) {
            $this->classifiedRepository->save($classifiedForm->getData(), true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'auction created');

            return $this->redirectToRoute('create_market_classified', [
                'id' => $auction->getId(),
            ]);
        }

        dump($auction);

        return $this->render('auction/auction/new.html.twig', [
            'auction' => $auction,
            'classifiedForm' => $classifiedForm->createView(),
        ]);
    }

    #[Route(path: '/show/{id}', name: 'show_auction')]
    public function show(Auction $classified): Response
    {
        return $this->render('auction/auction/show.html.twig', [
            'auction' => $classified,
        ]);
    }
}
