<?php

namespace App\Controller\Controller\Auction\Offer;

use App\Entity\Auction\Item;
use App\Entity\Auction\Offer;
use App\Form\OfferFormType;
use App\Repository\ItemRepository;
use App\Repository\OfferRepository;
use App\Service\CurrentUserService;
use App\Service\MathHelper;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/auction/offer')]
class OfferController extends AbstractController
{

    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly OfferRepository $offerRepository,
        private readonly ItemRepository $itemRepository,
        private readonly MathHelper $mathHelper
    ) {
    }

    #[Route(path: '/create/{id}', name: 'create_item_offer')]
    public function create(Item $item, Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());

        $offer = new Offer();
        $offer->setItem($item);
        $offer->setOwner($currentUser);

        $price = $item->getOffer() instanceof Offer ? $item->getOffer()->getPrice() : $item->getPrice();
        $sugegstedOffer = ($price + $this->mathHelper->getPercentIncrease(number: $price, percentage: 5));

        $offerForm = $this->createForm(OfferFormType::class, $offer, [
            'suggestedOffer' => $sugegstedOffer
        ]);

        $offerForm->handleRequest($request);
        if ($offerForm->isSubmitted() && $offerForm->isValid()) {

            if($currentUser === $item->getOwner()){
                $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'That\'s a bit silly, don\'t try to make an offer on your own item.');
                return $this->redirectToRoute('show_auction', ['id' => $item->getId()]);
            }

            if(!$item->getOffer() instanceof Offer && $offerForm->getData()?->getPrice() <= $item->getPrice()){
                $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'You\'re offer is lower than the starting price.');
                return $this->redirectToRoute('show_auction', ['id' => $item->getId()]);
            }

            if($offerForm->getData()?->getPrice() <= $item->getOffer()?->getPrice()){
                $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'you\'re offer must be higher than the current best offer.');
                return $this->redirectToRoute('show_auction', ['id' => $item->getId()]);
            }

            if($item->getOffer() instanceof Offer && $item->getOffer()->getOwner() === $currentUser){
                $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'You already have the highest offer.');
                return $this->redirectToRoute('show_auction', ['id' => $item->getId()]);
            }

            $this->offerRepository->save($offerForm->getData(), true);
            $item->setOffer($offer);
            $this->itemRepository->save($item, true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'item added');

            return $this->redirectToRoute('show_auction', [
                'id' => $item->getId(),
            ]);
        }

        return $this->render('auction/offer/new.html.twig', [
            'item' => $item,
            'offerForm' => $offerForm,
        ]);
    }

}