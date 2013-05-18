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
		$channel = $this->channel();

		// we register ourselves in the channel
		$channel->set('layer:json', $this);

		// set the correct content-type
		$channel->add('http:header', ['content-type', 'application/json']);

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
			catch (\app\Exception_Implemented $e)
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
		$channel->set('body', \json_encode($body));
	}

	/**
	 * ...
	 */
	protected function error_handler(\mjolnir\types\Channel $channel, $status, $message)
	{
		$channel->set('http:status', $status);
		$channel->set('body', ['error' => $message]);
	}

} # class
