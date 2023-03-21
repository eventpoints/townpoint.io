<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Market;

use App\DataTransferObjects\MarketItemFilterDto;
use App\Form\Filter\MarketItemFilterForm;
use App\Repository\ItemRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/market')]
class MarketController extends AbstractController
{
    public function __construct(
        private readonly ItemRepository $itemRepository,
        private readonly PaginatorInterface $paginator,
    ) {
    }

    /**
     * @throws \App\Exception\ShouldNotHappenException
     */
    #[Route(path: '/', name: 'market')]
    public function index(Request $request): Response
    {
        $marketItemFilterDto = new MarketItemFilterDto();
        $itemsQuery = $this->itemRepository->findByFilter($marketItemFilterDto, true);
        $marketItemPagination = $this->paginator->paginate($itemsQuery, $request->query->getInt('items-page', 1), 30, [
            'pageParameterName' => 'items-page',
        ]);

        $marketItemFilterForm = $this->createForm(MarketItemFilterForm::class, $marketItemFilterDto);
        $marketItemFilterForm->handleRequest($request);
        if ($marketItemFilterForm->isSubmitted() && $marketItemFilterForm->isValid()) {
            $itemsQuery = $this->itemRepository->findByFilter($marketItemFilterDto, true);
            $marketItemPagination = $this->paginator->paginate(
                $itemsQuery,
                $request->query->getInt('items-page', 1),
                30,
                [
                    'pageParameterName' => 'items-page',
                ]
            );

            return $this->render('market/index.html.twig', [
                'marketItemFilterForm' => $marketItemFilterForm,
                'marketItemPagination' => $marketItemPagination,
            ]);
        }

        return $this->render('market/index.html.twig', [
            'marketItemFilterForm' => $marketItemFilterForm,
            'marketItemPagination' => $marketItemPagination,
        ]);
    }
}
