<?php
/**
 * Author: tracerout
 * Date: 14.04.15 21:23
 */

namespace lbarulski\CacheTagsBundle\Invalidator\Proxy;

class Varnish implements ProxyInterface
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
	 * @var string|null
	 */
	private $hostHeader;
	
	/**
	 * @param string      $host
	 * @param int         $port
	 * @param string      $path
	 * @param int         $timeout
	 * @param string      $invalidationHeaderName
	 * @param string|null $hostHeader
	 */
	public function __construct($host, $port, $path, $timeout, $invalidationHeaderName, $hostHeader = null)
	{
		$this->host                   = $host;
		$this->port                   = $port;
		$this->path                   = $path;
		$this->timeout                = $timeout;
		$this->invalidationHeaderName = $invalidationHeaderName;
		$this->hostHeader             = $hostHeader;
	}

	/**
	 * @inheritdoc
	 */
	public function invalidate($tag)
	{
		$fp = fsockopen($this->host, $this->port, $errNo, $errStr, $this->timeout);

		if (false === is_resource($fp))
		{
			$exception = new \RuntimeException($errStr, $errNo);
			throw new \RuntimeException(sprintf('Unable to connect to Varnish on %s:%d', $this->host, $this->port), $errNo, $exception);
		}

		$hostHeader = $this->hostHeader ?: $this->host;
		
		$out = sprintf("BAN %s HTTP/1.1\r\n", $this->path);
		$out .= sprintf("Host: %s\r\n", $hostHeader);
		$out .= sprintf("%s: %s\r\n", $this->invalidationHeaderName, $tag);
		$out .= "Connection: Close\r\n\r\n";

		fwrite($fp, $out);
		fclose($fp);
	}
}
