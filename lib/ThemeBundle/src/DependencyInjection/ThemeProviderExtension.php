<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

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

        $definition = $container->getDefinition('fenris.theme_provider');
        $args = $definition->getArgument(0);
        foreach ($config['themes'] as $theme) {
            $args[] = new Reference($theme);
        }
        $definition->setArgument(0, $args);
    }

    public function getAlias(): string
    {
        return 'theme_provider';
    }
}
