<?php namespace mjolnir\foundation;

/**
 * Resource layer used primarily in theming.
 * 
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2013 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_Theme extends \app\Instantiatable implements \mjolnir\types\Layer
{
	use \app\Trait_Layer;

	/**
	 * ...
	 */
	function run()
	{
		$channel = $this->channel();

		// we register ourselves in the channel
		$channel->set('layer:theme', $this);
		
		$channel->set('theme:path', \app\Theme::path());
		$channel->set('theme:name', \app\Theme::title());
	}

} # class
