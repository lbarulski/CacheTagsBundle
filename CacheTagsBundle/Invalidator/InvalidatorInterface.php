<?php
/**
 * Author: tracerout
 * Date: 14.04.15 22:07
 */

namespace lbarulski\CacheTagsBundle\Invalidator;

use lbarulski\CacheTagsBundle\Tag\TagInterface;

interface InvalidatorInterface
{
	public function invalidateTag(TagInterface $tag);
}