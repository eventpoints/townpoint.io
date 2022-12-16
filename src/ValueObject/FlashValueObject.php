<?php

declare(strict_types = 1);

namespace App\ValueObject;

class FlashValueObject
{
    final public const TYPE_MESSAGE = 'message';

    final public const TYPE_ERROR = 'error';

    final public const TYPE_SUCCESS = 'success';

    final public const MESSAGE_SUCCESS_SAVED = 'all changes saved';
}
