<?php

namespace App\ValueObject;

class FlashValueObject
{
    public const TYPE_MESSAGE = 'message';
    public const TYPE_ERROR = 'error';
    public const TYPE_SUCCESS = 'success';
    public const MESSAGE_SUCCESS_SAVED = 'all changes saved';
}