<?php

namespace lbarulski\CacheTagsBundle\Annotation\CacheTag;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

/**
 * @Annotation
 * @Target("METHOD")
 */
class RequestAttribute implements ConfigurationInterface
{
	public const ALIAS = 'cache_tag_request_attribute';

	/**
	 * @Required()
	 * @var string
	 */
	public $name;

	/**
	 * Returns the alias name for an annotated configuration.
	 * @return string
	 */
	public function getAliasName()
	{
		return self::ALIAS;
	}

	/**
	 * Returns whether multiple annotations of this type are allowed
	 * @return bool
	 */
	public function allowArray()
	{
		return true;
	}
}
