<?php

namespace App\DataTransferObject;

use App\Enum\StatementTypeEnum;

final readonly class StatementFilterDto
{
    public function __construct(
        private null|string $keyword = null,
        private null|StatementTypeEnum $type = null
    ) {
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function getType(): ?StatementTypeEnum
    {
        return $this->type;
    }
}
