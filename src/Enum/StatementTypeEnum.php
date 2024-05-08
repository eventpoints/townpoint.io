<?php

namespace App\Enum;

enum StatementTypeEnum: string
{
    case EVENT = 'event';
    case CLASSIFIED = 'classified';
    case ANNOUNCEMENT = 'announcement';
    case RECOMMENDATION = 'recommendation';
    case LOST_AND_FOUND = 'lost_and_found';
    case QUESTION = 'question';
    case ITEM_FOR_SALE = 'item_for_sale';
    case LOOKING_TO_BUY = 'looking_to_buy';
    case LOCAL_BUSINESS_SERVICE = 'local_business_service';
    case CHANCE_ENCOUNTER = 'chance_encounter';

    public static function match(null|string $value): null|StatementTypeEnum
    {
        return match ($value) {
            self::EVENT->value => self::EVENT,
            self::CLASSIFIED->value => self::CLASSIFIED,
            self::ANNOUNCEMENT->value => self::ANNOUNCEMENT,
            self::RECOMMENDATION->value => self::RECOMMENDATION,
            self::LOST_AND_FOUND->value => self::LOST_AND_FOUND,
            self::QUESTION->value => self::QUESTION,
            self::ITEM_FOR_SALE->value => self::ITEM_FOR_SALE,
            self::LOOKING_TO_BUY->value => self::LOOKING_TO_BUY,
            self::LOCAL_BUSINESS_SERVICE->value => self::LOCAL_BUSINESS_SERVICE,
            default => null
        };
    }
}
