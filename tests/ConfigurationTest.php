<?php

namespace lbarulski\CacheTagsBundle\Tests;

use lbarulski\CacheTagsBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
	public function testResponseTagDefault()
	{
		$config = $this->process([['response' => []]]);

		$this->assertSame('X-CACHE-TAGS', $config['response']['tag']);
	}

	public function testVarnishProxyDefaults()
	{
		$config = $this->process([['proxies' => ['varnish' => [[]]]]]);

		$this->assertSame([
			'host'            => '127.0.0.1',
			'port'            => 80,
			'path'            => '/',
			'timeout'         => 1,
			'header'          => 'X-CACHE-TAG',
			'host_header'     => null,
			'ssl_verify_peer' => false,
			'headers'         => [],
		], $config['proxies']['varnish'][0]);
	}

	public function testHostHeaderAndHeaders()
	{
		$config = $this->process([[
			'response' => ['tag' => 'X-TAGS'],
			'proxies'  => ['varnish' => [[
				'host'        => 'varnish.internal',
				'port'        => 6081,
				'host_header' => 'www.example.com',
				'headers'     => [['name' => 'X-Foo', 'value' => 'bar']],
			]]],
		]]);

		$this->assertSame('X-TAGS', $config['response']['tag']);
		$varnish = $config['proxies']['varnish'][0];
		$this->assertSame('varnish.internal', $varnish['host']);
		$this->assertSame(6081, $varnish['port']);
		$this->assertSame('www.example.com', $varnish['host_header']);
		$this->assertSame([['name' => 'X-Foo', 'value' => 'bar']], $varnish['headers']);
	}

	public function testHeaderRequiresNameAndValue()
	{
		$this->expectException(InvalidConfigurationException::class);

		$this->process([['proxies' => ['varnish' => [['headers' => [['name' => 'X-Foo']]]]]]]);
	}

	private function process(array $configs)
	{
		// host_header is deprecated; the deprecation notice is expected, not a failure.
		set_error_handler(function ($level) {
			return E_USER_DEPRECATED === $level;
		});
		try
		{
			return (new Processor())->processConfiguration(new Configuration(), $configs);
		}
		finally
		{
			restore_error_handler();
		}
	}
}
