<?php
namespace Wiechert\DataTablesBundle\Configuration;


use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;


class DataTablesConfiguration implements ConfigurationInterface{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wiechert_data_tables');

        $rootNode
            ->children()
                ->arrayNode('Bundles')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('namespace')->end()
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

                                                  ->end()
                                          ->end()
                                          ->arrayNode('NamedTables')
                                                ->prototype('array')
                                                    ->children()
                                                        ->scalarNode('title')->end()
                                                        ->scalarNode('description')->end()
                                                        ->scalarNode('select_table')->end()
                                                        ->scalarNode('select_table_bundle')->end()
                                                        ->arrayNode('joins')
                                                            ->prototype('array')
                                                                ->children()
                                                                    ->scalarNode('join')->end()
                                                                    ->scalarNode('alias')->end()
                                                                ->end()
                                                            ->end()
                                                        ->end()
                                                        ->scalarNode('where_caluse')->end()
                                                    ->end()
                                                ->end()
                                          ->end()
                                    ->end()
                                ->end()
                            ->end()
                    ->end()
                ->end()

            ->end()->arrayNode('Strategies')
            ->prototype('scalar')->end()
            ->end()
        ->end();
        return $treeBuilder;
    }
//             ->prototype('array');


}