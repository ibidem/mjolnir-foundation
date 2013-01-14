<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Channel extends \app\Instantiatable implements \mjolnir\types\Channel
{
	use \app\Trait_Channel;

	const nominal = 0;
	const error = 1;

	/**
	 * @var int
	 */
	protected $status = self::nominal;

	/**
	 * @return string
	 */
	function render()
	{
		return $this->get('body', null);
	}

	/**
	 * @return int status
	 */
	function status()
	{
		return $this->status;
	}

	/**
	 * @return \app\Channel $this
	 */
	function status_is($status)
	{
		$this->status = $status;
		return $this;
	}

} # class
