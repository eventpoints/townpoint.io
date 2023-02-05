<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Market;

use App\Entity\Market\Item;
use App\Factory\image\ImageFactory;
use App\Form\MarketItemFormType;
use App\Repository\CommentRepository;
use App\Repository\ImageRepository;
use App\Repository\ItemRepository;
use App\Service\CurrentUserService;
use App\Service\ImageUploadService;
use App\ValueObject\FlashValueObject;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/market/item')]
class ItemController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly ItemRepository $itemRepository,
        private readonly ImageRepository $imageRepository,
        private readonly CommentRepository $commentRepository,
        private readonly ImageUploadService $imageUploadService,
        private readonly ImageFactory $imageFactory,
        private readonly PaginatorInterface $paginator
    ) {
    }

    #[Route(path: '/create', name: 'create_market_item')]
    public function create(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $item = new Item();
        $item->setOwner($currentUser);
        $itemForm = $this->createForm(MarketItemFormType::class, $item);

        $itemForm->handleRequest($request);
        if ($itemForm->isSubmitted() && $itemForm->isValid()) {
            $itemImages = $itemForm->get('images')
                ->getData();
            /** @var UploadedFile $itemImage */
            foreach ($itemImages as $itemImage) {
                $base64Image = $this->imageUploadService->processStatementPhoto($itemImage);
                $image = $this->imageFactory->create($base64Image->getEncoded(), $item);
                $this->imageRepository->save($image);
            }

            $this->itemRepository->save($itemForm->getData(), true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'item added');

            return $this->redirectToRoute('show_market_item', [
                'id' => $item->getId(),
            ]);
        }

        return $this->render('market/item/new.html.twig', [
            'itemForm' => $itemForm->createView(),
        ]);
    }

    #[Route(path: '/show/{id}', name: 'show_market_item')]
    public function show(Item $item, Request $request): Response
    {
        $commentsQuery = $this->commentRepository->findByMarketItem($item, true);
        $marketItemCommentsPagination = $this->paginator->paginate(
            $commentsQuery,
            $request->query->getInt('item-comments-page', 1),
            30,
            [
                'pageParameterName' => 'items-page',
            ]
        );

        return $this->render('market/item/show.html.twig', [
            'marketItemCommentsPagination' => $marketItemCommentsPagination,
            'item' => $item,
        ]);
    }
}
