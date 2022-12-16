<?php

declare(strict_types = 1);

namespace App\Service;

use App\Enum\ModeEnum;

class ModeService
{
    private string $mode;

    public function getMode(): string
    {
        return $this->mode ?? ModeEnum::PLAY->value;
    }

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }
}
