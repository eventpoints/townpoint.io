<?php

declare(strict_types = 1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('turbo', [
        'broadcast' => [
            'entity_template_prefixes' => [
                'App\Entity\\' => 'broadcast/',
            ],
        ],
    ]);
};
