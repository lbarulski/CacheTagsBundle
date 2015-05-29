<?php
/**
 * Author: tracerout
 * Date: 14.04.15 22:07
 */

namespace lbarulski\CacheTagsBundle\Invalidator\Proxy;

interface ProxyInterface
{
	/**
	 * @param string $tag
	 */
	public function invalidate($tag);
}