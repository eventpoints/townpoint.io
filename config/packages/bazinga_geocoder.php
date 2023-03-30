<?php

declare(strict_types=1);

use Bazinga\GeocoderBundle\Doctrine\ORM\GeocoderListener;
use Bazinga\GeocoderBundle\Mapping\Driver\AttributeDriver;
use Bazinga\GeocoderBundle\ProviderFactory\LocationIQFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('bazinga_geocoder', [
        'fake_ip' => '123.123.123.123',
        'providers' => [
            'locationIq' => [
                'factory' => LocationIQFactory::class,
                'options' => [
                    'api_key' => '%env(resolve:LOCATION_IQ_PRIVATE_KEY)%',
                ],
            ],
        ],
    ]);
};
