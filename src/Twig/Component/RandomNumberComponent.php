<?php

declare(strict_types = 1);

namespace App\Twig\Component;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use function App\Twig\rand;

#[AsLiveComponent('random_number')]
class RandomNumberComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public int $max = 1000;

    public function getRandomNumber(): int
    {
        return rand(0, $this->max);
    }
}
