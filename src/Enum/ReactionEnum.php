<?php

declare(strict_types = 1);

namespace App\Enum;

enum ReactionEnum: string
{
    case ADMIRE = 'ADMIRE';
    case RESPECT = 'RESPECT';
    case DISAGREE = 'DISAGREE';
    case DISAPPOINTED = 'DISAPPOINTED';
}
