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

		$themeconfig = $this->themeconfig();
		$channel->set('themeconfig', $themeconfig);

		$relaynode = $channel->get('relaynode');
		$driver = $relaynode->get('theme.driver');

		$themedriver = '\app\ThemeDriver_'.\app\Text::camelcase_from_dashcase($driver);
		$themedriver = $themedriver::instance();
		$themedriver->channel_is($channel);

		try
		{
			$render = $themedriver->render();
			$channel->set('body', $render);
		}
		catch (\Exception $exception)
		{
			if (\app\CFS::config('mjolnir/base')['development'])
			{
				throw $exception;
			}
			else # recovery mode
			{
				\mjolnir\log_exception($exception);
				$themedriver->reset();
				$themedriver->recover();
			}
		}
	}

	/**
	 * @return array theme configuration
	 */
	protected function themeconfig()
	{
		$settings = \app\CFS::config('mjolnir/themes');
		$environment = include DOCROOT.'environment'.EXT;
		$theme = $this->channel()->get('relaynode')->get('theme', null);

		if (isset($environment['themes']) && isset($environment['themes'][$theme]))
		{
			$themepath = $environment['themes'][$theme].DIRECTORY_SEPARATOR;
			$themefile = $themepath.$settings['themes.config'].EXT;
		}
		else # search for theme
		{
			$themepath = \app\CFS::dir
				(
					$settings['themes.dir'].DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR
				);

			$themefile = $themepath.$settings['themes.config'].EXT;
		}

		if ($themefile)
		{
			$themeconfig = include $themefile;
			$globalconfig = \app\CFS::config('mjolnir/themes');
			
			$this->channel()->set('themepath', $themepath);
			
			return \app\Arr::merge($themeconfig, $globalconfig);
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
