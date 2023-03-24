<?php

namespace App\Model;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class Card
{
    #[Assert\NotBlank]
    private string $holder;

    #[Assert\NotBlank]
    #[Assert\CardScheme(['VISA', 'MASTERCARD', 'MAESTRO'])]
    private int $number;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\Length(3)]
    private int $securityCode;

    private DateTimeImmutable $expireAt;

    /**
     * @return string
     */
    public function getHolder(): string
    {
        return $this->holder;
    }

    /**
     * @param string $holder
     */
    public function setHolder(string $holder): void
    {
        $this->holder = $holder;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getSecurityCode(): int
    {
        return $this->securityCode;
    }

    /**
     * @param int $securityCode
     */
    public function setSecurityCode(int $securityCode): void
    {
        $this->securityCode = $securityCode;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getExpireAt(): DateTimeImmutable
    {
        return $this->expireAt;
    }

    /**
     * @param DateTimeImmutable $expireAt
     */
    public function setExpireAt(DateTimeImmutable $expireAt): void
    {
        $this->expireAt = $expireAt;
    }

}