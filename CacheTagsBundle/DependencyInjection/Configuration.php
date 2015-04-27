<?php

namespace lbarulski\CacheTagsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cache_tags');

		/** @noinspection PhpUndefinedMethodInspection */
		$rootNode
			->addDefaultsIfNotSet()
			->children()
				->arrayNode('varnish')
					->addDefaultsIfNotSet()
					->children()
						->scalarNode('host')->defaultValue('127.0.0.1')->end()
						->integerNode('port')->defaultValue(80)->end()
						->scalarNode('path')->defaultValue('/')->end()
						->integerNode('timeout')->defaultValue(1)->end()
						->arrayNode('header')
							->addDefaultsIfNotSet()
							->children()
								->scalarNode('tags')->defaultValue('X-CACHE-TAGS')->end()
								->scalarNode('invalidation')->defaultValue('X-CACHE-TAG')->end()
							->end()
						->end()
					->end()
				->end()
			->end();
        return $treeBuilder;
    }
}
