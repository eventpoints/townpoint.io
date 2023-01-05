<?php

namespace App\DataTransferObjects;

use DateTime;
use DateTimeImmutable;

class EventFilterDto
{
    private null|string $title = null;
    private null|string $address = null;
    private null|DateTime $startAt = null;
    private null|DateTime $endAt = null;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return DateTime|null
     */
    public function getStartAt(): ?DateTime
    {
        return $this->startAt;
    }

    /**
     * @param DateTime|null $startAt
     */
    public function setStartAt(?DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }

    /**
     * @return DateTime|null
     */
    public function getEndAt(): ?DateTime
    {
        return $this->endAt;
    }

    /**
     * @param DateTime|null $endAt
     */
    public function setEndAt(?DateTime $endAt): void
    {
        $this->endAt = $endAt;
    }

}