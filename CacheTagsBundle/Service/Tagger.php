<?php
/**
 * Author: tomaszwojcik
 * Date: 27/04/15 12:22
 */

namespace lbarulski\CacheTagsBundle\Service;

use lbarulski\CacheTagsBundle\Tag\CacheTagInterface;
use Symfony\Component\HttpFoundation\Response;

class Tagger
{
	/** @var string */
	private $headerName;

	/**
	 * @param string $headerName
	 */
	public function __construct($headerName)
	{
		$this->headerName = $headerName;
	}

	/**
	 * @param Response       $response
	 * @param CacheTagInterface[] $tags
	 * @param bool           $replace
	 */
	public function tagResponse(Response $response, $tags, $replace = false)
	{
		$tagValues = [];
		foreach ($tags as $tag)
		{
			$tagValues[] = $tag->getCacheTag();
		}

		if (!$replace && $response->headers->has($this->headerName))
		{
			$responseTags = $response->headers->get($this->headerName);
			if ('' !== $responseTags)
			{
				$tagValues = array_merge(explode(',', $responseTags), $tagValues);
			}
		}

		$tagValues = array_unique($tagValues);

		if (empty($tagValues))
		{
			return;
		}

		$response->headers->set($this->headerName, implode(',', $tagValues));
	}
}
