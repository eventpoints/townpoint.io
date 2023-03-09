<?php

declare(strict_types = 1);

namespace App\Twig\Filter;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class What3WordsFilter extends AbstractExtension
{
    public function getFilters(): array
    {
        return [new TwigFilter('what3words', function (string $value): string {
            return $this->what3words($value);
        })];
    }

    public function what3words(string $value): string
    {
        $regex = '/(\/){3}\w+\.\w+\.\w+/';
        $replacement = '<a href="https://what3words.com/${0}" target="_blank" class="link-primary">${0}</a>';

        return preg_replace($regex, $replacement, $value) ?? $value;
    }
}
