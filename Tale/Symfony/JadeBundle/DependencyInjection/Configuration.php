<?php

namespace Tale\Symfony\JadeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * JadeExtension configuration structure.
 *
 */
class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jade');

        $rootNode
            ->children()
                ->booleanNode('pretty')->defaultFalse()->end()
                ->scalarNode('cache_dir')->defaultValue('%kernel.cache_dir%/jade')->end()
            ->end();

        return $treeBuilder;
    }
}