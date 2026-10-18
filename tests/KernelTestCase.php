<?php

namespace lbarulski\CacheTagsBundle\Tests;

use lbarulski\CacheTagsBundle\Tests\Fixtures\FakeVarnish;
use lbarulski\CacheTagsBundle\Tests\Fixtures\TestKernel;
use PHPUnit\Framework\TestCase;

abstract class KernelTestCase extends TestCase
{
	/** @var FakeVarnish[] */
	protected $varnishes = [];

	/** @var TestKernel */
	protected $kernel;

	protected function setUp(): void
	{
		$this->varnishes = [new FakeVarnish(), new FakeVarnish()];

		$this->kernel = new TestKernel([
			[
				'host'    => '127.0.0.1',
				'port'    => $this->varnishes[0]->getPort(),
				'timeout' => 2,
			],
			[
				'host'        => '127.0.0.1',
				'port'        => $this->varnishes[1]->getPort(),
				'timeout'     => 2,
				'header'      => 'X-CUSTOM-TAG',
				'host_header' => 'varnish.example.com',
				'headers'     => [['name' => 'X-Extra', 'value' => 'yes']],
			],
		]);

		// host_header is deprecated on purpose; the deprecation must not fail the boot.
		set_error_handler(function ($level) {
			return E_USER_DEPRECATED === $level;
		});
		try
		{
			$this->kernel->boot();
		}
		finally
		{
			restore_error_handler();
		}
	}

	protected function tearDown(): void
	{
		$this->kernel->shutdown();
		foreach ($this->varnishes as $varnish)
		{
			$varnish->close();
		}
		$this->removeDir(dirname($this->kernel->getCacheDir()));
	}

	private function removeDir($dir)
	{
		if (!is_dir($dir))
		{
			return;
		}
		foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $file)
		{
			$file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
		}
		rmdir($dir);
	}
}
