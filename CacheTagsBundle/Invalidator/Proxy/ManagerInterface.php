<?php

namespace lbarulski\CacheTagsBundle\Invalidator\Proxy;

interface ManagerInterface
{
	/**
	 * @return ProxyInterface[]|\SplObjectStorage
	 */
	public function getProxies();
}