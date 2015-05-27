<?php

namespace lbarulski\CacheTagsBundle\Invalidator;

use lbarulski\CacheTagsBundle\Tag\TagInterface;

interface InvalidatorInterface
{
	public function invalidate(TagInterface $tag);
}
