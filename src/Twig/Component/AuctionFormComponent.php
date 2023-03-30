<?php

declare(strict_types = 1);

namespace App\Twig\Component;

use App\Entity\Auction\Auction;
use App\Entity\Auction\Item;
use App\Form\AuctionFormType;
use App\Form\ItemFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent('auction_form')]
class AuctionFormComponent extends AbstractController
{
    use LiveCollectionTrait;
    use DefaultActionTrait;

    #[LiveProp(fieldName: 'data')]
    public null|Item $item = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ItemFormType::class, $this->item);
    }
}
