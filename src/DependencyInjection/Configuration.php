<?php

declare(strict_types=1);

namespace App\DDDBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treebuilder = new TreeBuilder('ddd');
        return $treebuilder;
    }
}
