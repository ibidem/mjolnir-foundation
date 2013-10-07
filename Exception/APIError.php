<?php namespace mjolnir\foundation;

/**
 * This exception is typically found in auto-generated code.
 *
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Exception_APIError extends \app\Exception
{
	/** @var array */
	protected $payload = null;

	/**
	 * ...
	 */
	function __construct($message = null, $payload = null)
	{
		if ($message !== null)
		{
			$this->message = $message;
		}
		else # no message
		{
			$this->message = 'API Error';
		}

		$this->payload = $payload;
	}

	/**
	 * @return array
	 */
	function payload()
	{
		return $this->payload;
	}

} # class
