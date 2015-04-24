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

	/** @var string */
	private $headerName;

	/**
	 * @param Repository $repository
	 * @param string     $headerName
	 */
	public function __construct(Repository $repository, $headerName)
	{
		$this->repository = $repository;
		$this->headerName = $headerName;
	}

	/**
	 * @param FilterResponseEvent $event
	 */
	public function onKernelResponse(FilterResponseEvent $event)
	{
		$response = $event->getResponse();

		$response->headers->set($this->headerName, join(',', $this->repository->getTags()));
	}
}
