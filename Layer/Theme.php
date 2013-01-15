<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
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

		$relaynode = $channel->get('relaynode');
		$driver = $relaynode->get('theme.driver');

		$themeconfig = $this->themeconfig();
		$channel->set('themeconfig', $themeconfig);

		$themedriver = '\app\ThemeDriver_'.$driver;
		$themedriver = $themedriver::instance();
		$themedriver->channel_is($channel);

		$render = $themedriver->render();

		$channel->body('body', $render);
	}

	/**
	 * @return array theme configuration
	 */
	protected function themeconfig()
	{
		$settings = \app\CFS::config('mjolnir/themes');
		$env_config = include DOCROOT.'environment'.EXT;
		$theme = $this->channel()->get('relaynode')->get('theme', null);
		$env_is_set = isset($env_config['themes']) && isset($env_config['themes'][$theme]);

		if ($env_is_set)
		{
			$theme_path = $env_config['themes'][$theme].DIRECTORY_SEPARATOR;
			$theme_config_file = $theme_path.$settings['themes.config'].EXT;
		}
		else # search for theme
		{
			$theme_path = \app\CFS::dir
				(
					$settings['themes.dir'].DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR
				);

			$theme_config_file = $theme_path.$settings['themes.config'].EXT;
		}

		if ($theme_config_file)
		{
			$theme_config = include $theme_config_file;
			$global_config = \app\CFS::config('mjolnir/themes');
			return \app\Arr::merge($theme_config, $global_config);
		}
		else # no theme configuration
		{
			throw new \app\Exception
				(
					'Missing theme configuration for ['.$theme.'].'
				);
		}
	}

} # class
