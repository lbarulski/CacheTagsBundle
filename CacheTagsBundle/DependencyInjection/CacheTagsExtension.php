<?php

namespace lbarulski\CacheTagsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CacheTagsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

		if (false === $container->hasParameter('cache_tags.varnish.host'))
		{
			$container->setParameter('cache_tags.varnish.host', $config['varnish']['host']);
		}

		if (false === $container->hasParameter('cache_tags.varnish.port'))
		{
			$container->setParameter('cache_tags.varnish.port', $config['varnish']['port']);
		}

		if (false === $container->hasParameter('cache_tags.varnish.path'))
		{
			$container->setParameter('cache_tags.varnish.path', $config['varnish']['path']);
		}

		if (false === $container->hasParameter('cache_tags.varnish.timeout'))
		{
			$container->setParameter('cache_tags.varnish.timeout', $config['varnish']['timeout']);
		}

		if (false === $container->hasParameter('cache_tags.varnish.header.tags'))
		{
			$container->setParameter('cache_tags.varnish.header.tags', $config['varnish']['header']['tags']);
		}

		if (false === $container->hasParameter('cache_tags.varnish.header.invalidation'))
		{
			$container->setParameter('cache_tags.varnish.header.invalidation', $config['varnish']['header']['invalidation']);
		}
    }
}
