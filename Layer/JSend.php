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

		$channel->set('body', \json_encode($formatted_body));
	}

} # class
