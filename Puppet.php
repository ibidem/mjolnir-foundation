<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
abstract class Puppet extends \app\Instantiatable implements \mjolnir\types\Puppet
{
	use \app\Trait_Puppet;

	#
	# Implementations extending this class should have a static grammar property
	# with an array in the format [singular] or [singular, plural] where names
	# are in all lower case and multi word names use spaces to delimit words.
	#

	/**
	 * @return string singular name for puppet
	 */
	static function singular()
	{
		return static::$grammar[0];
	}

	/**
	 * @return string plural name for puppet
	 */
	static function plural()
	{
		return isset(static::$grammar) && isset(static::$grammar[1]) ? static::$grammar[1] : static::singular().'s';
	}

} # class
