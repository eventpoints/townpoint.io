<?php

declare(strict_types = 1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('knp_paginator', [
        'page_range' => 5,
        'default_options' => [
            'page_name' => 'page',
            'sort_field_name' => 'sort',
            'sort_direction_name' => 'direction',
            'distinct' => true,
            'filter_field_name' => 'filterField',
            'filter_value_name' => 'filterValue',
        ],
        'template' => [
            'pagination' => 'pagination/custom.pagination.html.twig',
            'sortable' => '@KnpPaginator/Pagination/bootstrap_v5_bi_sortable_link.html.twig',
            'filtration' => '@KnpPaginator/Pagination/bootstrap_v5_filtration.html.twig',
        ],
    ]);
};
