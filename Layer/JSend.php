<?php namespace mjolnir\foundation;

/**
 * The following layer is based on the JSend standard proposed at
 * http://labs.omniti.com/labs/jsend
 *
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_JSend extends \app\Instantiatable implements \mjolnir\types\Layer
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
		// execution becomes unrecoverable, the system will still jsend
		// response back
		\app\Env::set
			(
				'error-500.redirect',
				\app\URL::href('mjolnir:api-jsend-500.route')
			);

		$channel = $this->channel();

		// we register ourselves in the channel
		$channel->set('layer:jsent', $this);

		// set the correct content-type
		$channel->add('http:header', ['content-type', 'application/json']);

		$jsent = $this;
		$channel->add_postprocessor
			(
				'jsent:formatter',
				function () use ($jsent)
				{
					$jsent->standardize($jsent->channel());
				}
			);
	}

	/**
	 * ...
	 */
	protected function standardize(\mjolnir\types\Channel $channel)
	{
		$body = $channel->get('body', null);

		if ($channel->status() === \app\Channel::nominal)
		{
			$formatted_body = \json_encode
				(
					[
						'status' => 'success',
						'data' => $body,
					]
				);
		}
		else # assume error
		{
			$formatted_body = \json_encode
				(
					[
						'status' => 'error',
						'message' => $body,
					]
				);
		}

		$channel->set('body', $formatted_body);
	}

} # class
