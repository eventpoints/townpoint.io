<?php

declare(strict_types = 1);

namespace App\Twig\Extension;

use ReflectionClass;
use ReflectionException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class InstanceOfExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [new TwigFilter('instanceof', function ($object): bool {
            return $this->isInstanceOf($object);
        })];
    }

    public function isInstanceOf(Object $object): bool
    {
        $class = get_class($object);
        $reflectionClass = new ReflectionClass($class);

        return $reflectionClass->isInstance($object);
    }
}
