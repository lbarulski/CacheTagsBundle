<?php

namespace lbarulski\CacheTagsBundle\Listener;

use lbarulski\CacheTagsBundle\Annotation\CacheTag\Plain;
use lbarulski\CacheTagsBundle\Annotation\CacheTag\RequestAttribute;
use lbarulski\CacheTagsBundle\Service\Repository;
use lbarulski\CacheTagsBundle\Tag\Plain as PlainTag;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class CacheTagListener
{
	/** @var Repository */
	private $repository;

	/**
	 * Constructor.
	 *
	 * @param Repository $repository
	 */
	public function __construct(Repository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * @param FilterControllerEvent|ControllerEvent $event
	 */
	public function requestAttribute($event)
	{
		$request = $event->getRequest();
		$aliasName = $this->getAliasName(RequestAttribute::ALIAS);

		/** @var RequestAttribute[] $configurations */
		$configurations = $request->attributes->get($aliasName);
		if (!$configurations)
		{
			return;
		}

		foreach ($configurations as $configuration)
		{
			$object = $request->attributes->get($configuration->name);
			if (null !== $object)
			{
				$this->repository->add($object);
			}
		}

	}

	/**
	 * @param FilterControllerEvent|ControllerEvent $event
	 */
	public function plain($event)
	{
		$request = $event->getRequest();
		$aliasName = $this->getAliasName(Plain::ALIAS);

		/** @var Plain[] $configurations */
		$configurations = $request->attributes->get($aliasName);
		if (!$configurations)
		{
			return;
		}

		foreach ($configurations as $configuration)
		{
			$object = new PlainTag($configuration->name);
			$this->repository->add($object);
		}

	}

	/**
	 * @param string $value
	 *
	 * @return string
	 */
	private function getAliasName($value)
	{
		return '_' . $value;
	}
}
