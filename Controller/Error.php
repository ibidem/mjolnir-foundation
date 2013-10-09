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
	 * Special action. Log a client side error.
	 */
	function action_log()
	{
		if (\app\Server::request_method() === 'POST')
		{
			if (isset($_POST['url'], $_POST['line'], $_POST['message']))
			{
				// cleanup url
				$base_config = \app\CFS::config('mjolnir/base');
				$url = $_POST['url'];
				$url = \str_replace('http://'.$base_config['domain'].$base_config['path'], '', $url);
				$url = \preg_replace('#^media/themes/[^0-9]+/[0-9\.]+(-complete)?/#', '', $url);

				$error_diagnostic = $_POST['message'].' ('.$_POST['location'].' -- '.$url.' @ Ln '.$_POST['line'].')';
				\mjolnir\shortlog('Client', $error_diagnostic);

				if ($_POST['trace'])
				{
					// clean trace
					$trace = \preg_replace('#(http(s)?:)?//'.$base_config['domain'].$base_config['path'].'media/themes/[^0-9]+/[0-9\.]+(-complete)?/#', '', $_POST['trace']);
					$trace = \preg_replace('#(http(s)?:)?//'.$base_config['domain'].$base_config['path'].'#', '', $trace);

					\mjolnir\masterlog('Client', $error_diagnostic."\n\t\t".\str_replace("\n", "\n\t\t", $trace)."\n");
				}
				else # no trace
				{
					\mjolnir\masterlog('Client', $error_diagnostic);
				}

			}
			else # unknown format
			{
				\mjolnir\log('ClientError', 'Unknown format. POST: '.\serialize($_POST));
			}
		}
		else # logging
		{
			\mjolnir\log('Client', 'Recieved unconventional logging request from '.\app\Server::client_ip());
		}
	}

	/**
	 * ...
	 */
	function json_500()
	{
		$this->channel()->set('http:status', '500 Internal Server Error');
		$this->channel()->add('http:header', [ 'content-type', 'application/json', true ]);

		# we're not logging the request since this is 99% of the time triggered
		# by the internal error handling which has already logged the error far
		# better then we can here; the 1% has likely dealt with it on it's own

		return [ 'error' => 'Internal Server Error' ];
	}

	/**
	 * ...
	 */
	function jsend_500()
	{
		$this->channel()->set('http:status', '500 Internal Server Error');
		$this->channel()->add('http:header', [ 'content-type', 'application/json', true ]);

		# we're not logging the request since this is 99% of the time triggered
		# by the internal error handling which has already logged the error far
		# better then we can here; the 1% has likely dealt with it on it's own

		return array
			(
				'status' => 'error',
				'message' => 'Internal Server Error'
			);
	}

	/**
	 * ...
	 */
	function api_404()
	{
		$this->channel()->set('http:status', '404 Not Found');
		$this->channel()->add('http:header', [ 'content-type', 'application/json', true ]);
		\mjolnir\log_exception(new \app\Exception_NotFound('Accessed Missing API.'));
		return [ 'error' => 'URL called is not a recognized API.' ];
	}

	/**
	 * ...
	 */
	function action_404()
	{
		$this->channel()->set('http:status', '404 Not Found');
		throw new \app\Exception_NotFound('URL called is not a recognized API.');
	}

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
		// bellow we don't need to check for command line usage since we are
		// in a controller which is only likely to be called by another
		// controller as part of a http request

		\mjolnir\log_exception($exception);

		if (\app\CFS::config('mjolnir/base')['development'])
		{
			throw $exception;
		}

		switch ($classname)
		{
			case 'Exception_NotFound':
				try
				{
					$this->channel()->set('http:status', '404 Not Found');

					return \app\ThemeView::fortarget('exception-NotFound')
						->pass('control', $this)
						->pass('context', $this);
				}
				catch (\Exception $e)
				{
					\mjolnir\log_exception($e);

					$wwwpath = \app\Env::key('www.path');
					if ($wwwpath !== null && \file_exists($wwwpath.'404'.EXT))
					{
						include $wwwpath.'404'.EXT;
						exit(1);
					}

					return 'Resource Not Found. Please verify the data provided.';
				}

			case 'Exception_NotAllowed':
				try
				{
					$this->channel()->set('http:status', '403 Forbidden');

					return \app\ThemeView::fortarget('exception-NotAllowed')
						->pass('control', $this)
						->pass('context', $this);
				}
				catch (\Exception $e)
				{
					\mjolnir\log_exception($e);

					$wwwpath = \app\Env::key('www.path');
					if ($wwwpath !== null && \file_exists($wwwpath.'403'.EXT))
					{
						include $wwwpath.'403'.EXT;
						exit(1);
					}

					return 'Operation Not Allowed. You require higher access privalages to perform the operation.';
				}

			case 'Exception_NotApplicable':
				try
				{
					$this->channel()->set('http:status', '403 Forbidden');

					return \app\ThemeView::fortarget('exception-NotApplicable')
						->pass('exception', $exception)
						->pass('control', $this)
						->pass('context', $this);
				}
				catch (\Exception $e)
				{
					\mjolnir\log_exception($e);

					$wwwpath = \app\Env::key('www.path');
					if ($wwwpath !== null && \file_exists($wwwpath.'500'.EXT))
					{
						include $wwwpath.'500'.EXT;
						exit(1);
					}

					return 'Not Applicable: '.$exception->getMessage();
				}

			case 'Exception_NotImplemented':
				try
				{
					$this->channel()->set('http:status', '501 Not Implemented');

					return \app\ThemeView::fortarget('exception-NotImplemented')
						->pass('control', $this)
						->pass('context', $this);
				}
				catch (\Exception $e)
				{
					\mjolnir\log_exception($e);

					$wwwpath = \app\Env::key('www.path');
					if ($wwwpath !== null && \file_exists($wwwpath.'500'.EXT))
					{
						include $wwwpath.'500'.EXT;
						exit(1);
					}

					return 'Not Implemented';
				}

			default:
				try
				{
					$this->channel()->set('http:status', '500 Internal Server Error');

					return \app\ThemeView::fortarget('exception-Unknown')
						->pass('control', $this)
						->pass('context', $this);
				}
				catch (\Exception $e)
				{
					\mjolnir\log_exception($e);

					$wwwpath = \app\Env::key('www.path');
					if ($wwwpath !== null && \file_exists($wwwpath.'500'.EXT))
					{
						include $wwwpath.'500'.EXT;
						exit(1);
					}

					return 'An unknown error has occured. We apologize for the inconvenience.';
				}
		}
	}

} # class
