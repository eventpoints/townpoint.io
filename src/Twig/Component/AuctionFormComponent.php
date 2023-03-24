<?php

declare(strict_types = 1);

namespace App\Twig\Component;

use App\Entity\Auction\Auction;
use App\Form\AuctionFormType;
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
    public null|Auction $classified = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(AuctionFormType::class, $this->classified);
    }
}
