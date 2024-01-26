<?php

namespace lbarulski\CacheTagsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CacheTagsExtension extends Extension
{
	const PROXY_INVALIDATOR_MANAGER = 'cache_tags.invalidator.proxy.manager';
	const PROXY_INVALIDATOR_VARNISH = 'cache_tags.invalidator.proxy.varnish';

	/**
	 * {@inheritdoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$configuration = new Configuration();
		$config        = $this->processConfiguration($configuration, $configs);

		$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		$loader->load('services.yml');
		$loader->load('taggers.yml');
		$loader->load('invalidators.yml');
		$loader->load('listeners.yml');

		$container->setParameter('cache_tags.response.tag', $config['response']['tag']);

		$managerDefinition = $container->getDefinition(self::PROXY_INVALIDATOR_MANAGER);

		foreach ($config['proxies']['varnish'] as $index => $varnishConfig)
		{
			$id  = sprintf('cache_tags.invalidator.proxy.varnish.%d', $index);

            $def = class_exists(ChildDefinition::class) ?
                new ChildDefinition(self::PROXY_INVALIDATOR_VARNISH)
                :
                new DefinitionDecorator(self::PROXY_INVALIDATOR_VARNISH);

			$def->setArguments([
				$varnishConfig['host'],
				$varnishConfig['port'],
				$varnishConfig['path'],
				$varnishConfig['timeout'],
				$varnishConfig['header'],
				$varnishConfig['host_header'],
				$varnishConfig['ssl_verify_peer'],
			]);

			$container->setDefinition($id, $def);
			$managerDefinition->addMethodCall('addProxy', [
				new Reference($id),
			]);
		}
	}
}
