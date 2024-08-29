<?php

declare(strict_types=1);

use App\Service\ApplicationTimeService\ApplicationTimeService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('twig', [
        'default_path' => '%kernel.project_dir%/templates',
        'form_themes' => [
            'bootstrap_5_layout.html.twig',
            'form/fields/selection_group.html.twig',
            'form/fields/entity_selection_group.html.twig',
            'form/fields/editor.html.twig',
        ],
    ]);
    if ($containerConfigurator->env() === 'test') {
        $containerConfigurator->extension('twig', [
            'strict_variables' => true,
        ]);
    }
};
