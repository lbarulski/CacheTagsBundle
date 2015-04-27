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
		$tags     = $this->repository->getTags();

		if ($response->headers->has($this->headerName))
		{
			$responseTags = $response->headers->get($this->headerName);
			if ('' !== $responseTags)
			{
				$tags = array_merge(explode(',', $responseTags), $tags);
			}
		}

		$tags = array_unique($tags);
		$response->headers->set($this->headerName, implode(',', $tags));
	}
}
