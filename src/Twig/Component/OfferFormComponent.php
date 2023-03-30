<?php

declare(strict_types = 1);

namespace App\Twig\Component;

use App\Entity\Auction\Item;
use App\Form\OfferFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent('item_offer_form', template: '/auction/offer/_form.component.html.twig')]
class OfferFormComponent extends AbstractController
{
    use LiveCollectionTrait;
    use DefaultActionTrait;

    public Item $item;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(OfferFormType::class);
    }
}
