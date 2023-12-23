<?php

declare(strict_types=1);

namespace App\DDDBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DDDExtension extends Extension
{
    private const CONFIG_PATH = __DIR__.'/../Resources/config';

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(self::CONFIG_PATH));
        $loader->load('services.php');
    }

    public function getAlias(): string
    {
        return 'ddd';
    }
}
