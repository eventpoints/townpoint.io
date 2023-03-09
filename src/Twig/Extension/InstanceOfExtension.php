<?php

declare(strict_types = 1);

namespace App\Twig\Extension;

use ReflectionClass;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class InstanceOfExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [new TwigFilter('instanceof', function ($object, string $class): bool {
            return $this->isInstanceOf($object, $class);
        })];
    }

    public function isInstanceOf($object, string $class): bool
    {
        $reflectionClass = new ReflectionClass($class);

        return $reflectionClass->isInstance($object);
    }
}
