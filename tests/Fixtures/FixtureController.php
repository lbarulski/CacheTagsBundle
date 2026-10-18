<?php

namespace lbarulski\CacheTagsBundle\Tests\Fixtures;

use lbarulski\CacheTagsBundle\Annotation\CacheTag;
use Symfony\Component\HttpFoundation\Response;

class FixtureController
{
	/**
	 * @CacheTag\Plain(name="article")
	 * @CacheTag\Plain(name="article-42")
	 */
	public function tagged()
	{
		return new Response('tagged');
	}

	public function untagged()
	{
		return new Response('untagged');
	}
}
