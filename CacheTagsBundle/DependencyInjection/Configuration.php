<?php

namespace lbarulski\CacheTagsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
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

		$this->addResponseNode($rootNode);
		$this->addProxiesNode($rootNode);

        return $treeBuilder;
    }

	/**
	 * @param ArrayNodeDefinition|NodeDefinition $rootNode
	 */
	private function addResponseNode(NodeDefinition $rootNode)
	{
		$responseNode    = $rootNode->children()->arrayNode('response');

		$responseTagNode = $responseNode->children()->scalarNode('tag');
		$responseTagNode->defaultValue('X-CACHE-TAGS')->end();
	}

	/**
	 * @param ArrayNodeDefinition|NodeDefinition $rootNode
	 */
	private function addProxiesNode(NodeDefinition $rootNode)
	{
		$proxiesNode = $rootNode->children()->arrayNode('proxies');

		$this->addVarnishNode($proxiesNode);
	}

	/**
	 * @param ArrayNodeDefinition $proxiesNode
	 */
	private function addVarnishNode(ArrayNodeDefinition $proxiesNode)
	{
		$varnishNode = $proxiesNode->children()->arrayNode('varnish');

		/** @var ArrayNodeDefinition $varnishOptions */
		$varnishOptions = $varnishNode->prototype('array');

		$varnishOptions->addDefaultsIfNotSet();

		$varnishOptions->children()->scalarNode('host')->defaultValue('127.0.0.1')->end();
		$varnishOptions->children()->integerNode('port')->defaultValue(80)->end();
		$varnishOptions->children()->scalarNode('path')->defaultValue('/')->end();
		$varnishOptions->children()->integerNode('timeout')->defaultValue(1)->end();
		$varnishOptions->children()->scalarNode('header')->defaultValue('X-CACHE-TAG')->end();
		$varnishOptions->children()->scalarNode('host_header')->defaultNull()->end();
        $varnishOptions->children()->booleanNode('ssl_verify_peer')->defaultFalse()->end();
	}
}
