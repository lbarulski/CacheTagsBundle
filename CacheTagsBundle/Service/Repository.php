<?php
/**
 * Author: tracerout
 * Date: 14.04.15 20:32
 */

namespace lbarulski\CacheTagsBundle\Service;

use lbarulski\CacheTagsBundle\Tag\CacheTagInterface;

class Repository
{
	/**
	 * @var CacheTagInterface[]
	 */
	private $tags = [];

	/**
	 * @param CacheTagInterface $tag
	 */
	public function add(CacheTagInterface $tag)
	{
		$this->tags[$tag->getCacheTag()] = $tag;
	}

	/**
	 * @return CacheTagInterface[]
	 */
	public function getTags()
	{
		return $this->tags;
	}

	/**
	 * @param CacheTagInterface[] $tags
	 *
	 * @return $this
	 */
	public function setTags(array $tags)
	{
		$this->tags = $tags;

		return $this;
	}
}
