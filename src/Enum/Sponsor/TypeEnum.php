<?php

namespace App\Enum\Sponsor;

enum TypeEnum : string
{
    case FINANCIAL = 'financial';
    case VENUE = 'venue';
    case FOOD = 'food';
    case BEVERAGES = 'beverages';
    case ADVERTISING = 'advertising';
    case MERCHANDISE = 'merchandise';
}