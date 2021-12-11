<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ThemeProviderExtension extends Extension
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        //        $definition = $container->getDefinition('fenris.theme_provider');
        //        if (null !== $config['themes']) {
        //            //            $definition->setArgument(0, new Reference($config['themes'][0]));
        //            //            $container->setAlias('fenris.themes', $config['themes']);
        //        }
    }

    public function getAlias(): string
    {
        return 'theme_provider';
    }
}
