<?php

namespace lbarulski\CacheTagsBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class InvalidateCommandTest extends KernelTestCase
{
	public function testInvalidatesEveryTagOnEveryProxy()
	{
		$output = new BufferedOutput(OutputInterface::VERBOSITY_VERBOSE);

		$exitCode = $this->runCommand(['command' => 'cache_tags:invalidate', 'tag' => ['article-1', 'article-2']], $output);

		$this->assertSame(0, $exitCode);
		$this->assertStringContainsString('Invalidating tag', $output->fetch());

		$first = $this->varnishes[0]->getRequests();
		$this->assertCount(2, $first);
		$this->assertStringStartsWith("BAN / HTTP/1.1\r\nHost: 127.0.0.1\r\n", $first[0]);
		$this->assertStringContainsString("\r\nX-CACHE-TAG: article-1\r\n", $first[0]);
		$this->assertStringContainsString("\r\nX-CACHE-TAG: article-2\r\n", $first[1]);
		$this->assertStringNotContainsString('X-Extra', $first[0]);
	}

	public function testProxyOptionsAreUsedInTheRequest()
	{
		$this->runCommand(['command' => 'cache_tags:invalidate', 'tag' => ['article-1']], new BufferedOutput());

		$second = $this->varnishes[1]->getRequests();
		$this->assertCount(1, $second);
		$this->assertStringStartsWith("BAN / HTTP/1.1\r\nHost: varnish.example.com\r\n", $second[0]);
		$this->assertStringContainsString("\r\nX-CUSTOM-TAG: article-1\r\n", $second[0]);
		$this->assertStringContainsString("\r\nX-Extra: yes\r\n", $second[0]);
		$this->assertStringContainsString("\r\nConnection: Close\r\n\r\n", $second[0]);
	}

	private function runCommand(array $input, BufferedOutput $output)
	{
		$application = new Application($this->kernel);
		$application->setAutoExit(false);

		return $application->run(new ArrayInput($input), $output);
	}
}
