<?php
/**
 * Author: tracerout
 * Date: 14.04.15 20:10
 */

namespace lbarulski\CacheTagsBundle\Listener;

use lbarulski\CacheTagsBundle\Service\Repository;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class Response
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

	/**
	 * @param FilterResponseEvent $event
	 */
	public function onKernelResponse(FilterResponseEvent $event)
	{
		$response = $event->getResponse();

		$response->headers->set('X-CACHE-TAGS', join(',', $this->repository->getTags()));
	}
}