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
	 * @param string $host
	 * @param int    $port
	 * @param string $path
	 * @param int    $timeout
	 */
	public function __constructor($host, $port, $path, $timeout)
	{
		$this->host    = $host;
		$this->port    = $port;
		$this->path    = $path;
		$this->timeout = $timeout;
	}

	/**
	 * @param TagInterface $tag
	 */
	public function invalidateTag(TagInterface $tag)
	{
		$fp = fsockopen($this->host, $this->port, $errNo, $errStr, $this->timeout);

		$out = "BAN " . $this->path . " HTTP/1.1\r\n";
		$out .= "Host: " . $this->host . "\r\n";
		$out .= "X-CACHE-TAG: " . ((string) $tag) . "\r\n";
		$out .= "Connection: Close\r\n\r\n";

		fwrite($fp, $out);
		fclose($fp);
	}
}