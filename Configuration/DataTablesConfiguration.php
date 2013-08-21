<?php
namespace Wiechert\DataTablesBundle\Configuration;


use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;


class DataTablesConfiguration implements ConfigurationInterface{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('Datatables');

        $rootNode
            ->children()
            ->arrayNode('Bundles')
            ->scalarNode('namsespace')->end()
            ->prototype('array')
            ->children()
                ->arrayNode('Tables')
                ->prototype('array')
                ->children()
                      ->scalarNode('title')->end()
                      ->scalarNode('display_name')->end()
                      ->arrayNode('Actions')
                      ->children()
                            ->arrayNode('JSActions')
                            ->treatNullLike(array('enabled' => false))
                            ->prototype('array')
                            ->children()
                                    ->scalarNode('name')->end()
                                    ->scalarNode('route')->end()
                                    ->end()
                            ->end()
                            ->end()

                            ->arrayNode('PHPActions')
                            ->treatNullLike(array('enabled' => false))
                            ->prototype('array')
                            ->children()
                                    ->scalarNode('name')->end()
                                    ->scalarNode('route')->end()
                                  ->end()
                            ->end()
                       ->end()
                      ->arrayNode('GlobalActions')
                      ->treatNullLike(array('enabled' => false))
                      ->children()
                             ->arrayNode('JSActions')
                             ->treatNullLike(array('enabled' => false))
                             ->prototype('array')
                             ->children()
                                    ->scalarNode('name')->end()
                                    ->scalarNode('route')->end()
                                    ->end()
                             ->end()->end()


                             ->arrayNode('PHPActions')
                             ->treatNullLike(array('enabled' => false))
                             ->prototype('array')
                             ->children()

                                        ->scalarNode('name')->end()
                                        ->scalarNode('route')->end()
                                        ->end()
                             ->end()
                             ->end()
                      ->end()->end()
                ->end()->end()->arrayNode('NamedTables')
            ->prototype('array');

        return $treeBuilder;
    }
}