<?php

declare(strict_types = 1);

namespace App\Twig;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('alert')]
class AlertComponent
{
    public string $type = 'success';

    public string $message;

    public function getIconClass(): string
    {
        return match ($this->type) {
            'success' => 'fa fa-circle-check',
            'danger' => 'fa fa-circle-exclamation',
        };
    }
}
