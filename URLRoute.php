<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class URLRoute extends \app\Instantiatable implements \mjolnir\types\URLRoute
{
	use \app\Trait_URLRoute;

	/**
	 * The pattern of a <segment>
	 *
	 * @var string
	 */
	protected static $url_key_regex     = '<([a-zA-Z0-9_]++)>';

	/**
	 * What can be part of a <segment> value
	 *
	 * @var string
	 */
	protected static $url_segment_regex = '[^/.,;?\n]++'; # all except non url chars

	/**
	 * what must be escaped in the route regex
	 *
	 * @var string
	 */
	protected static $url_escape_regex  = '[.\\+*?[^\\]${}=!|]';

	/**
	 * Used in generating URL from parameters
	 *
	 * @var string
	 */
	protected $url_pattern;

	/**
	 * Regex passed in when specifying pattern.
	 *
	 * @var array
	 */
	protected $url_parameter_regex = null;

	/**
	 * Used in matching to URLs
	 *
	 * @var string
	 */
	protected $url_regex = null;

	/**
	 * @return static $this
	 */
	function urlpattern($pattern, array $parameter_regex = null)
	{
		$this->url_pattern = $pattern;
		$this->url_parameter_regex = $parameter_regex;

		return $this;
	}

	/**
	 * @return boolean defined route matches?
	 */
	function check()
	{
		$url = $this->get('url', null);

		if ($url === null)
		{
			throw new \app\Exception('Impossible check, URL was not set.');
		}

		$url = \trim($url, '/');

		if ($this->url_regex === null)
		{
			$this->url_regex = static::regex_from_pattern($this->url_pattern, $this->url_parameter_regex);
		}

		if ($this->url_regex !== null && \preg_match($this->url_regex, $url))
		{
			return true;
		}

		// no match
		return false;
	}

	/**
	 * @return string url
	 */
	function url(array $params = null, array $query = null, $protocol = null)
	{
		if ($params == null)
		{
			$params = [];
		}

		// relative protocol?
		if ($protocol !== false)
		{
			$url = ($protocol === null ? \app\CFS::config('mjolnir/base')['protocol'] : $protocol.'://');
		}
		else # ommit protocol
		{
			$url = '';
		}

		if ($this->urlbase)
		{
			$url .= $this->urlbase;
		}
		else # no url base set
		{
			$base = \app\CFS::config('mjolnir/base');
			$url .= $base['domain'].$base['path'];
		}

		if ($query !== null)
		{
			$url = $url.static::generate_uri($this->url_pattern, $params);

			// we do not convert & to &amp; since it's more intuitive to
			// process afterwards then have every url require that as an
			// extra parameter creating harder to track errors

			if (\stripos($url, '?') === false)
			{
				return $url.'?'.\http_build_query($query);
			}
			else # pattern already contains query information
			{
				return $url.'&'.\http_build_query($query);
			}
		}
		else # no query
		{
			return $url.static::generate_uri($this->url_pattern, $params);
		}

		return null;
	}

	/**
	 * @return array context information
	 */
	function context()
	{
		$matches = null;
		if ( ! \preg_match($this->url_regex, $this->get('url'), $matches))
		{
			return null;
		}

		$context = null;
		foreach ($matches as $key => $value)
		{
			// skip unnamed keys
			if (\is_int($key))
			{
				continue;
			}

			$context[$key] = $value;
		}

		if (\strpos($this->url_pattern, '<action>') !== false && ! isset($context['action']))
		{
			// default context action is always index
			$context['action'] = 'index';
		}

		return $context;
	}

	// ------------------------------------------------------------------------
	// Helpers

	/**
	 * @return string regex
	 */
	protected static function regex_from_pattern($uri, array $regex = null)
	{
		// the URI should be considered literal except for keys and optional parts
		// escape everything preg_quote would escape except for : ( ) < >
		$expression = \preg_replace('#'.static::$url_escape_regex.'#', '\\\\$0', $uri);

		if (\strpos($expression, '(') !== false)
		{
			// make optional parts of the URI non-capturing and optional
			$expression = \str_replace
				(
					['(', ')'],
					['(?:', ')?'],
					$expression
				);
		}

		// insert default regex for keys
		$expression = \str_replace
			(
				['<', '>'],

				// named subpattern PHP4.3 compatible: (?P<key>regex)
				// http://php.net/manual/en/function.preg-match.php#example-4371
				['(?P<', '>'.static::$url_segment_regex.')'],
				$expression
			);

		$search = $replace = [];
		if ($regex !== null)
		{
			foreach ($regex as $key => $value)
			{
				$search[]  = "<$key>".static::$url_segment_regex;
				$replace[] = "<$key>$value";
			}
		}

		// replace the default regex with the user-specified regex
		return '#^'.\str_replace($search, $replace, $expression).'$#uD';
	}

	/**
	 * @return string
	 */
	protected static function generate_uri($url, array $params = null)
	{
		if (\strpos($url, '<') === false && \strpos($url, '(') === false)
		{
			// this is a static route, no need to replace anything
			return $url;
		}

		// cycle though all optional groups
		$matches = null;
		while (\preg_match('#\([^()]++\)#', $url, $matches)) # match is populated
		{
			// search for the matched value
			$search = $matches[0];

			// remove the parenthesis from the match as the replace
			$replace = \substr($matches[0], 1, -1);

			while (\preg_match('#'.static::$url_key_regex.'#', $replace, $matches))
			{
				list($key, $param) = $matches;

				if (isset($params[$param]))
				{
					// replace the key with the parameter value
					$replace = \str_replace($key, $params[$param], $replace);
				}
				else # don't have paramter
				{
					// this group has missing parameters
					$replace = '';
					break;
				}
			}

			// replace the group in the URI
			$url = \str_replace($search, $replace, $url);
		}

		// cycle though required paramters
		while (\preg_match('#'.static::$url_key_regex.'#', $url, $matches))
		{
			list($key, $param) = $matches;

			if ( ! isset($params[$param]))
			{
				$params_err = [];
				foreach ($params as $key => $value)
				{
					$params_err[] = "$key => $value";
				}

				$url = \htmlspecialchars($url);
				$params_err = \implode(', ', $params_err);
				throw new \app\Exception
					("Required route parameter [$param] not passed when trying to generate uri [$url] with params [$params_err]");
			}
			else # paramter is set
			{
				$url = \str_replace($key, $params[$param], $url);
			}
		}

		// trim all extra slashes from the URI
		return \preg_replace('#//+#', '/', \rtrim($url, '/'));
	}

} # class
