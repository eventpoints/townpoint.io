<?php

declare(strict_types = 1);

namespace App\Twig\Filter;

use NumberFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class What3WordsFilter extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('what3words', [$this, 'what3words']),
        ];
    }

    public function what3words(string $value): string
    {
        $regex = "/^\/*(?:\p{L}\p{M}*){1,}[.｡。･・︒។։။۔።।](?:\p{L}\p{M}*){1,}[.｡。･・︒។։။۔።।](?:\p{L}\p{M}*){1,}$/u";
        $replacement = '<span class="bg-primary text-white">' . '${0},${1},${2}' . '</span>';
        preg_replace($regex, $replacement, $value);
        return $value;
    }
}


