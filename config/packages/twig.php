<?php

declare(strict_types=1);

use App\Service\ModeService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('twig', [
        'date' => [
            'format' => 'd.m.Y',
            'interval_format' => '%%d days',
        ],
        'globals' => [
            'mode' => service(ModeService::class),
        ],
        'form_themes' => [
            'bootstrap_5_layout.html.twig',
        ],
        'default_path' => '%kernel.project_dir%/templates',
    ]);
    if ($containerConfigurator->env() === 'test') {
        $containerConfigurator->extension('twig', [
            'strict_variables' => true,
        ]);
    }
};
