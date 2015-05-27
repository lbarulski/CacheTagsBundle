<?php

namespace lbarulski\CacheTagsBundle\Invalidator\Proxy;

class Manager implements ManagerInterface
{
	/** @var ProxyInterface[]|\SplObjectStorage */
	private $proxies;

	public function __construct()
	{
		$this->proxies = new \SplObjectStorage;
	}

	/**
	 * @param ProxyInterface $proxy
	 */
	public function addProxy(ProxyInterface $proxy)
	{
		$this->proxies->attach($proxy);
	}

	/** {@inheritdoc} */
	public function getProxies()
	{
		return $this->proxies;
	}
}