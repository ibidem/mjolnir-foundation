<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_HTTP extends \app\Instantiatable implements \mjolnir\types\Layer
{
	use \app\Trait_Layer;

	/**
	 * ...
	 */
	function run()
	{
		$channel = $this->channel();
		$channel->set('layer:http', $this);

		$http = $this;
		$channel->add_preprocessor
			(
				'http:headers',
				function () use ($http)
				{
					$http->httpheaders_using($http->channel());
				}
			);
	}

	/**
	 * ...
	 */
	function recover()
	{
		$this->channel()
			->set
			(
				'http:status',
				$this->status_from_errortype
					($this->channel()->get('exception', null))
			);
	}

	/**
	 * Removes all headers.
	 */
	function reset()
	{
		$channel = $this->channel();

		// reset headers
		$channel->set('http:header', null);
		$channel->set('http:status', null);
	}

	/**
	 * ...
	 */
	protected function httpheaders_using(\mjolnir\types\Channel $channel)
	{
		$http_config = \app\CFS::config('mjolnir/http');

		$status = $channel->get('http:status', '200 OK');

		if (\strtolower($http_config['interface']) === 'fastcgi')
		{
			\header("Status: $status");
		}
		else # interface !== fastcgi
		{
			\header("HTTP/1.1 $status");
		}

		// eg. $pipeline->add('http:header', ['content-type', 'text/plain', true])

		$final_headers = [];
		foreach ($channel->get('http:header', []) as $header)
		{
			if (isset($header[3]) && $header[3] == false)
			{
				// the third parameter is interpreted as "replace" (just
				// like \header's second) since there are headers that can
				// appear multiple times

				\header
				(
					"{$header[0]}: {$header[1]}",
					false # don't overwrite previous header
				);
			}
			else # non-repeatable header
			{
				$final_headers[\strtolower($header[0])] = $header[1];
			}
		}

		foreach ($final_headers as $type => $header)
		{
			\header("{$type}: {$header}");
		}
	}

	/**
	 * @return string status
	 */
	protected function status_from_errortype(\Exception $exception)
	{
		if (\is_a($exception, '\app\Exception_NotFound'))
		{
			$status = '404 Not Found';
		}
		else if (\is_a($exception, '\app\Exception_NotAllowed'))
		{
			$status = '403 Forbidden';
		}
		else if (\is_a($exception, '\app\Exception_NotImplemented'))
		{
			$status = '501 Not Implemented';
		}
		else # unknown type
		{
			$status = '500 Internal Server Error';
		}

		return $status;
	}

} # class
