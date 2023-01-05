<?php

declare(strict_types = 1);

namespace App\DataTransferObjects;

use DateTimeImmutable;

class EventFilterDto
{
    private null|string $title = null;

    private null|string $address = null;

    private null|DateTimeImmutable $startAt = null;

    private null|DateTimeImmutable $endAt = null;

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

    public function getStartAt(): ?DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(?DateTimeImmutable $startAt): void
    {
        $this->startAt = $startAt;
    }

    public function getEndAt(): ?DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?DateTimeImmutable $endAt): void
    {
        $this->endAt = $endAt;
    }
}
