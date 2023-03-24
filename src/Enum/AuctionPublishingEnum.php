<?php

declare(strict_types = 1);

namespace App\Enum;

enum AuctionPublishingEnum: string
{
    case DRAFT = 'draft';
    case REVIEWED = 'reviewed';
    case REJECTED = 'rejected';
    case PUBLISHED = 'published';
}
