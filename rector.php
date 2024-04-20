<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    // define sets of rules
        $rectorConfig->sets([
            LevelSetList::UP_TO_PHP_82,
            SetList::STRICT_BOOLEANS,
            SetList::TYPE_DECLARATION,
            SetList::CODE_QUALITY
        ]);
};
