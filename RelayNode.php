<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class RelayNode extends \app\Instantiatable implements \mjolnir\types\RelayNode
{
	use \app\Trait_RelayNode;

	/**
	 * @return \mjolnir\types\RelayNode
	 */
	static function instance(array $source = null)
	{
		$instance = parent::instance();

		$instance->metadata_is($source);

		return $instance;
	}

} # class
