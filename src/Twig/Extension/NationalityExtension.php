<?php

namespace App\Twig\Extension;

use App\Data\Nationality;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class NationalityExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('nationality', fn (string $countryCode): string => $this->getNationality($countryCode)),
        ];
    }

    public function getNationality(string $countryCode): string
    {
        return Nationality::getNationalities()[$countryCode];
    }
}
