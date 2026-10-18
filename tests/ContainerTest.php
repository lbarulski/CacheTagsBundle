<?php

namespace lbarulski\CacheTagsBundle\Tests;

use lbarulski\CacheTagsBundle\Command\InvalidateTagCommand;
use lbarulski\CacheTagsBundle\Invalidator\Proxy\Varnish;

class ContainerTest extends KernelTestCase
{
	public function testBundleServicesAreWired()
	{
		$container = $this->kernel->getContainer();

		$this->assertInstanceOf(InvalidateTagCommand::class, $container->get('cache_tags.command.invalidate_tag'));
		$this->assertTrue($container->has('cache_tags.tagger'));
		$this->assertTrue($container->has('cache_tags.listener.invalidator'));
		$this->assertSame('X-CACHE-TAGS', $container->getParameter('cache_tags.response.tag'));
	}

	public function testEveryConfiguredVarnishProxyIsRegistered()
	{
		$proxies = $this->kernel->getContainer()->get('cache_tags.invalidator.proxy.manager')->getProxies();

		$this->assertCount(2, $proxies);
		foreach ($proxies as $proxy)
		{
			$this->assertInstanceOf(Varnish::class, $proxy);
		}
	}
}
