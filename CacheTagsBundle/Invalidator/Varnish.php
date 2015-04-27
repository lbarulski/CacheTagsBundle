<?php
/**
 * Author: tracerout
 * Date: 14.04.15 21:23
 */

namespace lbarulski\CacheTagsBundle\Invalidator;

use lbarulski\CacheTagsBundle\Tag\TagInterface;

class Varnish implements InvalidatorInterface
{
	/**
	 * @var string
	 */
	private $host;

	/**
	 * @var int
	 */
	private $port;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var int
	 */
	private $timeout;

	/**
	 * @var string
	 */
	private $invalidationHeaderName;

	/**
	 * @param string $host
	 * @param int    $port
	 * @param string $path
	 * @param int    $timeout
	 * @param string $invalidationHeaderName
	 */
	public function __constructor($host, $port, $path, $timeout, $invalidationHeaderName)
	{
		$this->host                   = $host;
		$this->port                   = $port;
		$this->path                   = $path;
		$this->timeout                = $timeout;
		$this->invalidationHeaderName = $invalidationHeaderName;
	}

	/**
	 * @param TagInterface $tag
	 */
	public function invalidateTag(TagInterface $tag)
	{
		$fp = fsockopen($this->host, $this->port, $errNo, $errStr, $this->timeout);

		$out = sprintf("BAN %s HTTP/1.1\r\n", $this->path);
		$out .= sprintf("Host %s\r\n", $this->host);
		$out .= sprintf("%s: %s\r\n", $this->invalidationHeaderName, (string) $tag);
		$out .= "Connection: Close\r\n\r\n";

		fwrite($fp, $out);
		fclose($fp);
	}
}
