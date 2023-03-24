<?php

declare(strict_types = 1);

namespace App\Twig\Component;

use App\Entity\Auction\Auction;
use App\Form\AuctionFormType;
use App\Form\Payment\CardFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent('payment_card_form', template: '/components/payment/payment_card_form.twig')]
class CardFormComponent extends AbstractController
{
    use LiveCollectionTrait;
    use DefaultActionTrait;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CardFormType::class);
    }
}
