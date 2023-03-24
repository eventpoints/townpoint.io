<?php

declare(strict_types = 1);

use Stripe\StripeClient;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\', __DIR__ . '/../src/')
        ->exclude([
            __DIR__ . '/../src/DependencyInjection/',
            __DIR__ . '/../src/Entity/',
            __DIR__ . '/../src/Kernel.php',
        ]);

    $services->set(StripeClient::class)
        ->args(['%env(STRIPE_PRIVATE_KEY)%']);

    $services->set('trix.form.type', 'App\Form\Type\TrixTextEditorType')
        ->tag('form.type');
};
