<?php

namespace App\Enum;

enum FlashMessageEnum: string
{
    case MESSAGE = 'message';
    case ERROR = 'error';
    case SUCCESS = 'success';
}
