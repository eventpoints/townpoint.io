<?php

declare(strict_types = 1);

namespace App\Enum;

enum ModeEnum: string
{
    case PLAY = 'PLAY';
    case WORK = 'WORK';
    case LEARN = 'LEARN';
}
