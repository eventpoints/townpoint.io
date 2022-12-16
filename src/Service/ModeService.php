<?php

namespace App\Service;

use App\Enum\ModeEnum;

class ModeService
{
    private string $mode;

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode ?? ModeEnum::PLAY->value;
    }

    /**
     * @param string $mode
     */
    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

}