<?php
/**
 * Author: tracerout
 * Date: 14.04.15 20:10
 */

namespace lbarulski\CacheTagsBundle\Listener;

use lbarulski\CacheTagsBundle\Service\Repository;
use lbarulski\CacheTagsBundle\Service\Tagger;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ResponseListener
{
	/** @var Repository */
	private $repository;

	/** @var Tagger */
	private $tagger;

	/**
	 * @param Repository $repository
	 * @param Tagger     $tagger
	 */
	public function __construct(Repository $repository, Tagger $tagger)
	{
		$this->repository = $repository;
		$this->tagger     = $tagger;
	}

	/**
	 * @param FilterResponseEvent $event
	 */
	public function onKernelResponse(FilterResponseEvent $event)
	{
		if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType())
		{
			return;
		}

		$response = $event->getResponse();
		$tags     = $this->repository->getTags();

		$this->tagger->tagResponse($response, $tags);
	}
}
