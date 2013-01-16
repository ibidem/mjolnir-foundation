<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Router
{
	/**
	 * Check all routes for match.
	 */
	static function check_all()
	{
		$routes = \app\CFS::config('routes');
		
		$url = \app\Server::request_uri();

		// format: [ key, regex, allowed methods ]
		foreach ($routes as $pattern => $route_info)
		{
			// translate pattern
			$pattern = static::translate_pattern($pattern);

			$pattern = \trim($pattern, '/');

			if ( ! isset($route_info[1]))
			{
				$regex_pattern = [];
			}
			else # got regex
			{
				$regex_pattern = $route_info[1];
			}

			// create route pattern
			$matcher = \app\URLRoute::instance()
				->urlpattern($pattern, $regex_pattern)
				->set('url', $url);

			if ($matcher->check())
			{
				// retrieve the allowed methods
				if ( ! isset($route_info[2]))
				{
					$methods = ['GET'];
				}
				else if (\is_string($route_info[2]))
				{
					$methods = [$route_info[2]];
				}
				else # is set and is not string; assume array
				{
					$methods = \array_map
						(
							function ($str)
							{
								return \strtoupper($str);
							},
							$route_info[2]
						);
				}

				// attempt match
				foreach ($methods as $method)
				{
					if (\app\Server::request_method() === $method)
					{
						if (\is_array($route_info[0]))
						{
							\reset($route_info);
							$key = \key($route_info[0]);
							$binding = $route_info[0][$key];
						}
						else # not array
						{
							$key = $route_info[0];
							$binding = $route_info[0];
						}

						// retrieve format
						$format = 'html';
						if (\preg_match('#.*\.(?<format>[a-z0-9-_]+)$#', $key, $matches))
						{
							$format = $matches['format'];
						}

						$prefix = 'action_';
						if ($format !== 'html')
						{
							$prefix = $format.'_';
						}

						// retrieve format stack
						$route_stacks = \app\CFS::config('mjolnir/layer-stacks');

						// build faux relay object
						$default_action = $prefix.'index';
						$relay = array
							(
								'matcher' => $matcher,
								'controller' =>  static::resolve_controller_name($binding),
								'action' => $default_action,
								'prefix' => $prefix,
							);

						// execute
						$route_stacks[$format]($relay, $key);
						exit;
					}
				}
			}
		}
	}

	/**
	 * @return \app\URLRoute or null
	 */
	static function route($key)
	{
		$routes = \app\CFS::config('routes');

		foreach ($routes as $pattern => $route_info)
		{
			if ( ! \is_array($route_info[0]))
			{
				if ($route_info[0] === $key)
				{
					if (isset($route_info[1]))
					{
						return static::pattern_for($pattern, $route_info[1]);
					}
					else # not pattern regex given
					{
						return static::pattern_for($pattern, []);
					}
				}
			}
			else # array given
			{
				\reset($route_info[0]);
				if (\key($route_info[0]) === $key)
				{
					if (isset($route_info[1]))
					{
						return static::pattern_for($pattern, $route_info[1]);
					}
					else # not pattern regex given
					{
						return static::pattern_for($pattern, []);
					}
				}
			}
		}

		return null;
	}

	/**
	 * @return \app\URLRoute
	 */
	protected static function pattern_for($pattern, $regex_pattern)
	{
		$pattern = \trim($pattern, '/');

		// create route pattern
		return \app\URLRoute::instance()
			->urlpattern($pattern, $regex_pattern);
	}

	/**
	 * @return string controller
	 */
	protected static function resolve_controller_name($key)
	{
		$key = \preg_replace('#(\..*)#', '', $key);

		$key_parts = \explode('-', $key);

		$controller_name = \app\Arr::implode
			(
				'',
				$key_parts,
				function ($k, $value)
				{
					return \ucfirst($value);
				}
			);

		return '\app\Controller_'.$controller_name;
	}

	/**
	 * @return string
	 */
	protected static function translate_pattern($pattern)
	{
		if (\app\Lang::targetlang() !== 'en-us')
		{
			// load route translations
			$translations = \app\Lang::file('routes');

			if (isset($translations[$pattern]))
			{
				return $translations[$pattern];
			}
			else # failed to translate
			{
				return $pattern;
			}
		}
		else # en-us
		{
			return $pattern;
		}
	}

} # class
