<?php

namespace lbarulski\CacheTagsBundle\Tests\Fixtures;

use lbarulski\CacheTagsBundle\CacheTagsBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel implements CompilerPassInterface
{
	/** @var array */
	private $varnishProxies;

	public function __construct(array $varnishProxies)
	{
		$this->varnishProxies = $varnishProxies;
		parent::__construct('test', true);
	}

	public function registerBundles(): iterable
	{
		return [
			new FrameworkBundle(),
			new SensioFrameworkExtraBundle(),
			new CacheTagsBundle(),
		];
	}

	public function registerContainerConfiguration(LoaderInterface $loader): void
	{
		$loader->load(function (ContainerBuilder $container) {
			$container->loadFromExtension('framework', [
				'secret'      => 'test',
				'annotations' => ['enabled' => true],
			]);
			// Sensio registers the controller annotation listener only when a feature is enabled.
			$container->loadFromExtension('sensio_framework_extra', [
				'router'   => ['annotations' => false],
				'request'  => ['converters' => false],
				'view'     => ['annotations' => false],
				'cache'    => ['annotations' => true],
				'security' => ['annotations' => false],
			]);
			$container->loadFromExtension('cache_tags', [
				'response' => [],
				'proxies'  => ['varnish' => $this->varnishProxies],
			]);
			$container->register(FixtureController::class, FixtureController::class)->setPublic(true);
		});
	}

	protected function build(ContainerBuilder $container): void
	{
		$container->addCompilerPass($this, PassConfig::TYPE_BEFORE_OPTIMIZATION);
	}

	/** Exposes the bundle services so tests can fetch them from the compiled container. */
	public function process(ContainerBuilder $container): void
	{
		foreach ($container->getDefinitions() as $id => $definition)
		{
			if (0 === strpos($id, 'cache_tags.') && !$definition->isAbstract())
			{
				$definition->setPublic(true);
			}
		}
	}

	public function getCacheDir(): string
	{
		return sys_get_temp_dir() . '/cache-tags-bundle-tests/' . spl_object_hash($this) . '/cache';
	}

	public function getLogDir(): string
	{
		return sys_get_temp_dir() . '/cache-tags-bundle-tests/' . spl_object_hash($this) . '/logs';
	}
}
