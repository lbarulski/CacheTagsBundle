<?php
/**
 * Author: tracerout
 * Date: 14.04.15 20:46
 */

namespace lbarulski\CacheTagsBundle\Tag;

class Plain implements TagInterface
{
	/**
	 * @var string
	 */
	private $value;

	/**
	 * @param string $value
	 */
	public function __construct($value)
	{
		$this->value = $value;
	}

	/** {@inheritdoc} */
	public function getTag()
	{
		return $this->value;
	}
}
