<?php
/**
 * Author: tracerout
 * Date: 14.04.15 20:10
 */

namespace lbarulski\CacheTagsBundle\Listener;

use lbarulski\CacheTagsBundle\Service\Repository;
use lbarulski\CacheTagsBundle\Service\Tagger;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

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
	 * @param FilterResponseEvent|ResponseEvent $event
	 */
	public function onKernelResponse($event)
	{
		// isMasterRequest() is removed in Symfony 6.0, isMainRequest() exists since 5.3
		if (!(method_exists($event, 'isMainRequest') ? $event->isMainRequest() : $event->isMasterRequest()))
		{
			return;
		}

		$response = $event->getResponse();
		$tags     = $this->repository->getTags();

		$this->tagger->tagResponse($response, $tags);
	}
}
