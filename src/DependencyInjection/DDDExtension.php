<?php

declare(strict_types=1);

namespace App\DDDBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DDDExtension extends Extension implements PrependExtensionInterface
{
    private const CONFIG_PATH = __DIR__ . '/../Resources/config';

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(self::CONFIG_PATH));
        $loader->load('services.php');
    }

    public function getAlias(): string
    {
        return 'ddd';
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'mappings' => [
                    'ddd' => [
                        'is_bundle' => false,
                        'type' => 'attribute',
                        'dir' => __DIR__ . '/../Domain',
                        'prefix' => 'App\DDDBundle\Domain',
                        'alias' => 'ddd',
                    ],
                ],
            ],
        ]);
        
        $container->prependExtensionConfig('doctrine_migrations', [
            'migrations_paths' => [
                'App\DDDBundle\Infrastructure\Persistence\Migrations' => '@DDDBundle/Infrastructure/Persistence/Migrations'
            ],
        ]);
    }
}
