<?php
/**
 * Author: prgTW
 * Date: 24/04/15 20:56
 */

namespace lbarulski\CacheTagsBundle\Command;

use lbarulski\CacheTagsBundle\Invalidator\InvalidatorInterface;
use lbarulski\CacheTagsBundle\Tag\Plain;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InvalidateTagCommand extends Command
{
	const ARGUMENT_TAG = 'tag';

	/** @var InvalidatorInterface */
	private $invalidator;

	/**
	 * @param InvalidatorInterface $invalidator
	 */
	public function __construct(InvalidatorInterface $invalidator)
	{
		parent::__construct();
		$this->invalidator = $invalidator;
	}

	/** {@inheritdoc} */
	protected function configure()
	{
		parent::configure();

		$this->setName('cache_tags:invalidate');
		$this->addArgument(self::ARGUMENT_TAG, InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'Tag name(s) to invalidate');
		$this->setDescription('Invalidates tag(s)');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$verbosity = $output->getVerbosity();
		foreach ($input->getArgument(self::ARGUMENT_TAG) as $tag)
		{
			if ($verbosity >= OutputInterface::VERBOSITY_VERBOSE)
			{
				$output->writeln(sprintf('Invalidating tag <comment>%s</comment>', $tag));
			}

			$this->invalidator->invalidateTag(new Plain($tag));
		}
	}
}
