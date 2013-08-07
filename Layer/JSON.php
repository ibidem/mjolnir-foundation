<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_JSON extends \app\Instantiatable implements \mjolnir\types\Layer
{
	use \app\Trait_Layer;

	/**
	 * ...
	 */
	function run()
	{
		if (\function_exists('xdebug_disable'))
		{
			#
			# When we're testing APIs we're almost always using a program to
			# test that we're getting the correct response; so we're looking at
			# a view interpreting json not a view interpreting html; hence the
			# HTML error message just shows up as a big mess and also disturbs
			# various other systems we might have in place for gracefully
			# reporting or handling the error.
			#

			// Disable stack traces by xdebug when this layer is used
			\xdebug_disable();
		}

		// setup custom error page; if the request encounters a FatalError and
		// execution becomes unrecoverable, the system will still json'ish
		// response back
		\app\Env::set
			(
				'error-500.redirect',
				\app\URL::href('mjolnir:api-json-500.route')
			);

		$channel = $this->channel();

		// we register ourselves in the channel
		$channel->set('layer:json', $this);

		// set the correct content-type
		$channel->add('http:header', ['content-type', 'application/json', true]);

		$json = $this;
		$channel->add_postprocessor
			(
				'json:formatter',
				function () use ($json)
				{
					$json->standardize($json->channel());
				}
			);
	}

	/**
	 * ...
	 */
	function recover()
	{
		$channel = $this->channel();
		$exception = $channel->get('exception', null);

		if ($exception !== null)
		{
			try
			{
				throw $exception;
			}
			catch (\app\Exception_NotAllowed $e)
			{
				$this->error_handler($channel, 403, 'Not Found');
			}
			catch (\app\Exception_NotFound $e)
			{
				$this->error_handler($channel, 404, 'Not Found');
			}
			catch (\app\Exception_NotApplicable $e)
			{
				$this->error_handler($channel, 500, $e->getMessage());
			}
			catch (\app\Exception_NotImplemented $e)
			{
				$this->error_handler($channel, 501, 'Not Implemented');
			}
			catch (\app\Exception $e)
			{
				$this->error_handler($channel, 500, 'Internal Server Error');
			}
		}
		else # error state, but no exception
		{
			$this->error_handler($channel, 500, 'Unknown Logic Error');
		}
	}

	/**
	 * ...
	 */
	protected function standardize(\mjolnir\types\Channel $channel)
	{
		$body = $channel->get('body', null);
		$channel->set('body', $body !== null ? \json_encode($body) : '');
	}

	/**
	 * ...
	 */
	protected function error_handler(\mjolnir\types\Channel $channel, $status, $message)
	{
		$channel->set('http:status', $status);
		$this->channel()->add('http:header', [ 'content-type', 'application/json', true ]);
		$channel->set('body', ['error' => $message]);
	}

} # class
