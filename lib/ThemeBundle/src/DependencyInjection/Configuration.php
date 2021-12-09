<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('theme_provider');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->children()
            ->scalarNode('provider')
            ->defaultNull()
            ->info('Articles themes');

        return $treeBuilder;
    }
}
