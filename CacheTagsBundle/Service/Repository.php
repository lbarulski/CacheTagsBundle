<?php
/**
 * Author: tracerout
 * Date: 14.04.15 20:32
 */

namespace lbarulski\CacheTagsBundle\Service;

use lbarulski\CacheTagsBundle\Tag\TagInterface;

class Repository
{
	/**
	 * @var TagInterface[]
	 */
	private $tags = [];

	/**
	 * @param TagInterface $tag
	 */
	public function add(TagInterface $tag)
	{
		$this->tags[] = $tag;
	}

	/**
	 * @return TagInterface[]
	 */
	public function getTags()
	{
		return $this->tags;
	}
}
