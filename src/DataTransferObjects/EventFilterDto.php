<?php

declare(strict_types = 1);

namespace App\DataTransferObjects;

use DateTime;

class EventFilterDto
{
    private null|string $title = null;

    private null|string $address = null;

    private null|DateTime $startAt = null;

    private null|DateTime $endAt = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getStartAt(): ?DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(?DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }

    public function getEndAt(): ?DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(?DateTime $endAt): void
    {
        $this->endAt = $endAt;
    }
}
