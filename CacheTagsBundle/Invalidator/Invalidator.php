<?php

namespace lbarulski\CacheTagsBundle\Invalidator;

use lbarulski\CacheTagsBundle\Service\Repository;
use lbarulski\CacheTagsBundle\Tag\TagInterface;

class Invalidator implements InvalidatorInterface
{
	/**
	 * @var Repository
	 */
	private $repository;

	/**
	 * @param Repository $repository
	 */
	public function __construct(Repository $repository)
	{
		$this->repository = $repository;
	}

	public function invalidate(TagInterface $tag)
	{
		$this->repository->add($tag);
	}
}