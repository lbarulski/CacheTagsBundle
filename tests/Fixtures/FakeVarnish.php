<?php

namespace lbarulski\CacheTagsBundle\Tests\Fixtures;

/** Listens on a free local port and records the raw requests sent to it. */
class FakeVarnish
{
	/** @var resource */
	private $server;

	/** @var int */
	private $port;

	public function __construct()
	{
		$this->server = stream_socket_server('tcp://127.0.0.1:0', $errNo, $errStr);
		if (false === $this->server)
		{
			throw new \RuntimeException($errStr, $errNo);
		}
		$this->port = (int) substr(strrchr(stream_socket_get_name($this->server, false), ':'), 1);
	}

	public function getPort()
	{
		return $this->port;
	}

	/** @return string[] raw requests received so far */
	public function getRequests()
	{
		$requests = [];
		while ($connection = @stream_socket_accept($this->server, 0))
		{
			$requests[] = stream_get_contents($connection);
			fclose($connection);
		}

		return $requests;
	}

	public function close()
	{
		fclose($this->server);
	}
}
