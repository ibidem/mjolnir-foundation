<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Controller_Error extends \app\Instantiatable implements \mjolnir\types\Controller
{
	use \app\Trait_Controller;

	/**
	 * ...
	 */
	function process_error()
	{
		$exception = $this->channel()->get('exception', null);

		if ($exception !== null)
		{
			$classname = \preg_replace('#^.*\\\#', '', \get_class($exception));
			return $this->handle_exception($exception, $classname);
		}
		else # no exception; failure state
		{
			return $this->handle_exception(new \Exception('Exception was not set; errror controller failed to process.'), 'Exception');
		}
	}

	/**
	 * ...
	 */
	protected function handle_exception($exception, $classname)
	{
		// @todo more elegant exception reporting

		switch ($classname)
		{
			case 'Exception_NotFound':
				return 'not found';

			case 'Exception_NotAllowed':
				return 'not allowed';

			case 'Exception_NotApplicable':
				return 'not applicable';

			case 'Exception_NotImplemented':
				return 'not implemented';
				
			default:
				return 'unknown';
		}
	}

} # class
