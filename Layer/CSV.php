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
class Layer_CSV extends \app\Instantiatable implements \mjolnir\types\Layer
{
	use \app\Trait_Layer;

	/**
	 * ...
	 */
	function run()
	{
		$channel = $this->channel();

		// we register ourselves in the channel
		$channel->set('layer:csv', $this);

		// set the correct content-type
		$channel->add('http:header', ['content-type', 'text/csv']);

		$csv = $this;
		$channel->add_postprocessor
			(
				'csv:formatter',
				function () use ($csv)
				{
					$csv->standardize($csv->channel());
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
			$formatted_body = \app\Text::csv($body);
		}
		else # assume error
		{
			$formatted_body = $body;
		}

		$channel->set('body', $formatted_body);
	}

} # class
