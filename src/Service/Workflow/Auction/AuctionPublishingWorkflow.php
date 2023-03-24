<?php

namespace App\Service\Workflow\Auction;

use App\Entity\Auction\Auction;
use App\Enum\AuctionPublishingEnum;
use LogicException;
use Symfony\Component\Workflow\WorkflowInterface;

class AuctionPublishingWorkflow
{

    public function __construct(
        private readonly WorkflowInterface $auctionPublishingWorkflow
    )
    {}

    public function toReview(Auction $auction): void
    {
        try {
            $this->auctionPublishingWorkflow->apply($auction, AuctionPublishingEnum::REVIEWED->value);
        } catch (LogicException $logicException) {
            throw new $logicException;
        }
    }

    public function toPublished(Auction $auction): void
    {
        try {
            $this->auctionPublishingWorkflow->apply($auction, AuctionPublishingEnum::PUBLISHED->value);
        } catch (LogicException $logicException) {
            throw new $logicException;
        }
    }

    public function toRejected(Auction $auction): void
    {
        try {
            $this->auctionPublishingWorkflow->apply($auction, AuctionPublishingEnum::PUBLISHED->value);
        } catch (LogicException $logicException) {
            throw new $logicException;
        }
    }

}