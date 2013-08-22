<?php

namespace Wiechert\DataTablesBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;
use Wiechert\DataTablesBundle\Configuration\DataTablesConfiguration;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class WiechertDataTablesExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $configs[] = Yaml::parse(  $loader->getLocator()->locate("config.yml"));

        $dataTablesconfiguration = new DataTablesConfiguration();
        $dataTableconfig = $this->processConfiguration($dataTablesconfiguration, $configs);


        $container->setParameter('datatables.bundles', $dataTableconfig['Bundles']);
        $container->setParameter('datatables.strategies', $dataTableconfig['Strategies']);

    }
}
