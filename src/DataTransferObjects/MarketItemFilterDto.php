<?php

namespace App\DataTransferObjects;

class MarketItemFilterDto
{
    private null|string $title = null;
    private null|string $minPrice = null;
    private null|string $maxPrice = null;
    private null|string $currency = null;
    private null|string $condition = null;

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
    public function getMinPrice(): ?string
    {
        return $this->minPrice;
    }

    /**
     * @param string|null $minPrice
     */
    public function setMinPrice(?string $minPrice): void
    {
        $this->minPrice = $minPrice;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     */
    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string|null
     */
    public function getCondition(): ?string
    {
        return $this->condition;
    }

    /**
     * @param string|null $condition
     */
    public function setCondition(?string $condition): void
    {
        $this->condition = $condition;
    }

    /**
     * @return string|null
     */
    public function getMaxPrice(): ?string
    {
        return $this->maxPrice;
    }

    /**
     * @param string|null $maxPrice
     */
    public function setMaxPrice(?string $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }

}