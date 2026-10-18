<?php

namespace lbarulski\CacheTagsBundle\Tests;

use lbarulski\CacheTagsBundle\Tests\Fixtures\FixtureController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ListenerTest extends KernelTestCase
{
	public function testPlainAnnotationTagsTheResponse()
	{
		$response = $this->kernel->handle($this->request('tagged'));

		$this->assertSame('tagged', $response->getContent());
		$this->assertSame('article,article-42', $response->headers->get('X-CACHE-TAGS'));
	}

	public function testPlainAnnotationFillsTheTaggerRepository()
	{
		$this->kernel->handle($this->request('tagged'));

		$tags = $this->kernel->getContainer()->get('cache_tags.tagger.repository')->getTags();
		$this->assertSame(['article', 'article-42'], array_keys($tags));
	}

	public function testResponseWithoutAnnotationHasNoTagHeader()
	{
		$response = $this->kernel->handle($this->request('untagged'));

		$this->assertFalse($response->headers->has('X-CACHE-TAGS'));
	}

	public function testSubRequestIsNotTagged()
	{
		$this->kernel->handle($this->request('tagged'));

		$sub      = $this->request('untagged');
		$response = $this->kernel->handle($sub, HttpKernelInterface::SUB_REQUEST);

		$this->assertFalse($response->headers->has('X-CACHE-TAGS'));
	}

	private function request($action)
	{
		$request = Request::create('/');
		$request->attributes->set('_controller', FixtureController::class . '::' . $action);

		return $request;
	}
}
