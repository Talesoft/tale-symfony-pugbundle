<?php

namespace Tale\Symfony\JadeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class JadeExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('jade.yml');

        $config = $this->processConfiguration(
            $this->getConfiguration($configs, $container),
            $configs
        );

        $container->setParameter('jade.options', $config);
    }
}