<?php

namespace Fintem\FeatureToggleBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class FintemFeatureToggleExtension.
 */
class FintemFeatureToggleExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (empty($config)) {
            return;
        }

        $cacheServiceId = $config['cache']['id'];
        if (null === $cacheServiceId) {
            return;
        }

        $definition = $container->getDefinition('feature_toggle.model.feature');
        $definition->addMethodCall('setCache', [new Reference($cacheServiceId)]);
        $definition->addMethodCall('setCacheKeyGenerator', [new Reference('feature_toggle.cache_key_generator')]);
    }
}
