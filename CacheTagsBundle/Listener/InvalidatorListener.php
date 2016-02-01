<?php

namespace lbarulski\CacheTagsBundle\Listener;

use lbarulski\CacheTagsBundle\Invalidator\Proxy\ManagerInterface;
use lbarulski\CacheTagsBundle\Service\Repository;

class InvalidatorListener
{
	/** @var Repository */
	private $repository;

	/** @var ManagerInterface */
	private $manager;

	/**
	 * @param Repository       $repository
	 * @param ManagerInterface $manager
	 */
	public function __construct(Repository $repository, ManagerInterface $manager)
	{
		$this->repository = $repository;
		$this->manager    = $manager;
	}

	public function invalidate()
	{
		$tags = $this->repository->getTags();

		foreach ($tags as $tag)
		{
			$this->invalidateTag($tag->getCacheTag());
		}

		$this->repository->setTags([]);
	}

	/**
	 * @param string $tagValue
	 */
	private function invalidateTag($tagValue)
	{
		$proxies  = $this->manager->getProxies();
		foreach ($proxies as $proxy)
		{
			$proxy->invalidate($tagValue);
		}
	}
}
