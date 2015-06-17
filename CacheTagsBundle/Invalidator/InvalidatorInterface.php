<?php

namespace lbarulski\CacheTagsBundle\Invalidator;

use lbarulski\CacheTagsBundle\Tag\CacheTagInterface;

interface InvalidatorInterface
{
	/**
	 * @param CacheTagInterface $tag
	 */
	public function invalidate(CacheTagInterface $tag);
}
