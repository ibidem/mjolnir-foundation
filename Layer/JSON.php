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
	protected function standardize(\mjolnir\types\Channel $channel)
	{
		$body = $channel->get('body', null);
		$channel->set('body', \json_encode($body));
	}

} # class
