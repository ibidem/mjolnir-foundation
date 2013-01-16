<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Server
{
	/**
	 * @return string
	 */
	static function request_method()
	{
		if (isset($_SERVER['REQUEST_METHOD']))
		{
			// Use the server request method
			return \strtoupper($_SERVER['REQUEST_METHOD']);
		}
		else # REQUEST_METHOD not set
		{
			// Default to GET requests
			return 'GET';
		}
	}
	
	/**
	 * @return string 
	 */
	static function client_ip()
	{
		if 
		(
			isset($_SERVER['HTTP_X_FORWARDED_FOR'])
			&& isset($_SERVER['REMOTE_ADDR'])
			&& \in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost', 'localhost.localdomain'))
		)
		{
			// Use the forwarded IP address, typically set when the
			// client is using a proxy server.
			// Format: "X-Forwarded-For: client1, proxy1, proxy2"
			$client_ips = \explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

			return \array_shift($client_ips);
		}
		elseif 
		(
			isset($_SERVER['HTTP_CLIENT_IP'])
			&& isset($_SERVER['REMOTE_ADDR'])
			&& \in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost', 'localhost.localdomain'))
		)
		{
			// use the forwarded IP address, typically set when the
			// client is using a proxy server.
			$client_ips = \explode(',', $_SERVER['HTTP_CLIENT_IP']);

			return \array_shift($client_ips);
		}
		elseif (isset($_SERVER['REMOTE_ADDR']))
		{
			// the remote IP address
			return $_SERVER['REMOTE_ADDR'];
		}
		
		return '0.0.0.0';
	}
	
	/**
	 * @param string url
	 * @param int 303 (see other), 301 (permament), 307 (temporary)
	 */
	static function redirect($url, $type = 303)
	{
		if ($type === 303)
		{
			\app\GlobalEvent::fire('http:status', 'HTTP/1.1 303 See Other');
		}
		else if ($type == 301)
		{
			\app\GlobalEvent::fire('http:status', 'HTTP/1.1 301 Moved Permanently');
		}
		else if ($type == 307)
		{
			\app\GlobalEvent::fire('http:status', 'HTTP/1.1 307 Temporary Redirect');
		}
		
		// redirect to...
		\app\GlobalEvent::fire('http:attributes', [ 'location' => $url ]);
		
		// perform the redirect
		\app\GlobalEvent::fire('http:send-headers');
		
		exit;
	}
	
	/**
	 * @return string location identifier (null if not found)
	 */
	static function request_uri()
	{
		static $detected_uri = null;

		// did we do this already?
		if (isset($detected_uri))
		{
			return $detected_uri;
		}

		if ( ! empty($_SERVER['PATH_INFO']))
		{
			// PATH_INFO does not contain the docroot or index
			$uri = $_SERVER['PATH_INFO'];
		}
		else # empty PATH_INFO
		{
			// REQUEST_URI and PHP_SELF include the docroot and index
			if (isset($_SERVER['REQUEST_URI']))
			{
				/**
				 * We use REQUEST_URI as the fallback value. The reason
				 * for this is we might have a malformed URL such as:
				 *
				 *  http://localhost/http://example.com/judge.php
				 *
				 * which parse_url can't handle. So rather than leave empty
				 * handed, we'll use this.
				 */
				$uri = $_SERVER['REQUEST_URI'];

				if ($request_uri = \parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
				{
					// Valid URL path found, set it.
					$uri = $request_uri;
				}

				// Decode the request URI
				$uri = \rawurldecode($uri);
			}
			elseif (isset($_SERVER['PHP_SELF']))
			{
				$uri = $_SERVER['PHP_SELF'];
			}
			elseif (isset($_SERVER['REDIRECT_URL']))
			{
				$uri = $_SERVER['REDIRECT_URL'];
			}
			else # failed to detect
			{
				return $detected_uri = null;
			}

			$config = \app\CFS::config('mjolnir/base');

			// get the path from the base URL
			$url_base = \parse_url($config['domain'].$config['path'], PHP_URL_PATH);

			if (\strpos($uri, $url_base) === 0)
			{
				// remove the base URL from the URI
				$uri = (string) \substr($uri, \strlen($url_base));
			}
		}
		
		// remove path
		$base_config = \app\CFS::config('mjolnir/base');
		if (\substr($uri, 0, \strlen($base_config['path']) ) == $base_config['path'])
		{
			if (\strlen($base_config['path']) == \strlen($uri))
			{
				$uri = '';
			}
			else # uri is larger
			{
				$uri = \substr($uri, \strlen($base_config['path']), \strlen($uri));
			}

		}

		return $detected_uri = \trim($uri, '/');
	}

} # class
