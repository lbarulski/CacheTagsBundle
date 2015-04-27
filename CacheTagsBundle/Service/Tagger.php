<?php
/**
 * Author: tomaszwojcik
 * Date: 27/04/15 12:22
 */

namespace lbarulski\CacheTagsBundle\Service;

use lbarulski\CacheTagsBundle\Tag\TagInterface;
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
	 * @param Response                $response
	 * @param TagInterface[]|string[] $tags
	 * @param bool                    $replace
	 */
	public function tagResponse(Response $response, $tags, $replace = false)
	{
		if (!$replace && $response->headers->has($this->headerName))
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
