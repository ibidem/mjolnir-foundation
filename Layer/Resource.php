<?php namespace mjolnir\foundation;

/**
 * Resource layer used primarily in theming.
 *
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012, 2013 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_Resource extends \app\Instantiatable implements \mjolnir\types\Layer
{
	use \app\Trait_Layer;

	/**
	 * ...
	 */
	function run()
	{
		$channel = $this->channel();
		$relaynode = $channel->get('relaynode');

		// we register ourselves in the channel
		$channel->set('layer:resource', $this);

		// set theme config
		$themeconfig = $this->themeconfig();
		$channel->set('themeconfig', $themeconfig);

		// create theme object
		$themename = $relaynode->get('theme', null);
		$themepath = $this->themepath();
		$theme = \app\Theme::instance($themename, $themepath);
		$theme->channel_is($channel);
		$channel->set('theme', $theme);

		// run driver
		$driver = $relaynode->get('theme.driver');

		try
		{
			$themedriver = '\app\ThemeDriver_'.\app\Text::camelcase_from_dashcase($driver);
			$themedriver = $themedriver::instance();
			$themedriver->channel_is($channel);
		}
		catch (\Exception $exception)
		{
			\mjolnir\log_exception($exception);
			$this->channel()->set('http:status', '404 Not Found');
			$this->channel()->set('body', null);
			return;
		}

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
	 * @return string path
	 */
	protected function themepath()
	{
		$settings = \app\CFS::config('mjolnir/themes');
		$environment = \app\Env::key('environment.config');
		$themename = $this->channel()->get('relaynode')->get('theme', null);

		if (isset($environment['themes']) && isset($environment['themes'][$themename]))
		{
			$themepath = $environment['themes'][$themename].DIRECTORY_SEPARATOR;

		}
		else # search for theme
		{
			$themepath = \app\CFS::dir
				(
					$settings['themes.dir'].DIRECTORY_SEPARATOR.$themename.DIRECTORY_SEPARATOR
				);
		}

		return $themepath;
	}

	/**
	 * @return array theme configuration
	 */
	protected function themeconfig()
	{
		$settings = \app\CFS::config('mjolnir/themes');
		$themename = $this->channel()->get('relaynode')->get('theme', null);

		$themepath = $this->themepath();

		$themefile = $themepath.$settings['themes.config'].EXT;

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
					'Missing theme configuration for ['.$themename.'].'
				);
		}
	}

} # class
