<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container) {
    $container->extension('doctrine', [
        'orm' => [
            'auto_mapping' => true,
            'mappings' => [
                'DDDBundle' => [
                    'is_bundle' => true,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Domain/StoredEvent',
                    'prefix' => 'App\Domain\StoredEvent',
                    'alias' => 'DDDBundle'
                ],
            ],
        ],
    ]);
};
