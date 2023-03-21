<?php

declare(strict_types = 1);

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Twig\Extra\TwigExtraBundle\TwigExtraBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle;
use Symfony\UX\Turbo\TurboBundle;
use Symfony\UX\Autocomplete\AutocompleteBundle;
use Symfony\Bundle\MercureBundle\MercureBundle;
use Symfony\UX\Dropzone\DropzoneBundle;
use Symfony\UX\TwigComponent\TwigComponentBundle;
use Symfony\UX\LiveComponent\LiveComponentBundle;

return [
    FrameworkBundle::class => [
        'all' => true,
    ],
    DoctrineBundle::class => [
        'all' => true,
    ],
    DoctrineMigrationsBundle::class => [
        'all' => true,
    ],
    DebugBundle::class => [
        'dev' => true,
    ],
    TwigBundle::class => [
        'all' => true,
    ],
    WebProfilerBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    TwigExtraBundle::class => [
        'all' => true,
    ],
    SecurityBundle::class => [
        'all' => true,
    ],
    MonologBundle::class => [
        'all' => true,
    ],
    MakerBundle::class => [
        'dev' => true,
    ],
    SensioFrameworkExtraBundle::class => [
        'all' => true,
    ],
    WebpackEncoreBundle::class => [
        'all' => true,
    ],
    KnpPaginatorBundle::class => [
        'all' => true,
    ],
    EasyAdminBundle::class => [
        'all' => true,
    ],
    TurboBundle::class => [
        'all' => true,
    ],
    AutocompleteBundle::class => [
        'all' => true,
    ],
    MercureBundle::class => [
        'all' => true,
    ],
    DropzoneBundle::class => [
        'all' => true,
    ],
    TwigComponentBundle::class => [
        'all' => true,
    ],
    LiveComponentBundle::class => [
        'all' => true,
    ],
];
