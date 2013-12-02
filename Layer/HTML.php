<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_HTML extends \app\Instantiatable implements \mjolnir\types\Layer
{
	use \app\Trait_Layer;

	/**
	 * ...
	 */
	function run()
	{
		$channel = $this->channel();

		// we register ourselves in the channel
		$channel->set('layer:html', $this);

		$this->metadata_is(\app\CFS::config('mjolnir/html'));

		$html = $this;
		$channel->add_postprocessor
			(
				'html:formatter',
				function () use ($html)
				{
					$html->wrap($html->channel());
				}
			);
	}

	/**
	 * ...
	 */
	function recover()
	{
		$this->set('crawlers', false);
		$exception = $this->channel()->get('exception', null);

		if ($exception !== null)
		{
			if (\is_a($exception, '\app\Exception_LiveDump'))
			{
				\var_dump($exception->variable()); die;
			}
		}
	}

	/**
	 * ...
	 */
	function reset()
	{
		$this->metadata_is(\app\CFS::config('mjolnir/html'));
	}

	/**
	 * Retrieve a single property.
	 *
	 * @return mixed
	 */
	protected function prop($key, $default = null, $channel_prefix = 'html:')
	{
		// try to retrieve the channel property
		// fallback to configuration value
		// finally, fallback to hardcoded default
		return $this->channel()->get($channel_prefix.$key, $this->get($key, $default));
	}

	/**
	 * Retrieve the complete collection of properties.
	 *
	 * @return array
	 */
	protected function props($key, $default = null, $channel_prefix = 'html:')
	{
		$array1 = $this->channel()->get($channel_prefix.$key);
		$array2 = $this->get($key);

		// try to retrieve the channel property
		// fallback to configuration value
		// finally, fallback to hardcoded default
		if ($array1 !== null)
		{
			if ($array2 !== null)
			{
				return \app\Arr::merge($array1, $array2);
			}
			else # $array2 is null
			{
				return $array1;
			}
		}
		else if ($array2 !== null)
		{
			return $array2;
		}
		else # use default
		{
			return $default;
		}
	}

	/**
	 * Retrieve the complete unique set of properties.
	 *
	 * @return array
	 */
	protected function prop_index($key, $default = null, $channel_prefix = 'html:')
	{
		$array1 = $this->channel()->get($channel_prefix.$key);
		$array2 = $this->get($key, $default);

		// try to retrieve the channel property
		// fallback to configuration value
		// finally, fallback to hardcoded default
		if ($array1 !== null)
		{
			if ($array2 !== null)
			{
				return \app\Arr::index($array1, $array2);
			}
			else # $array2 is null
			{
				return $array1;
			}
		}
		else if ($array2 !== null)
		{
			return $array2;
		}
		else # use default
		{
			return $default;
		}
	}

	/**
	 * @return string
	 */
	protected function html_before(\mjolnir\types\Channel $channel)
	{
		$html_before = $this->prop('doctype', '<!DOCTYPE html>')."\n";
		// appcache manifest
		if ($this->prop('appcache') !== null)
		{
			$html_before .= <<<EOS
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" manifest="'.$this->prop('appcache').'"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" manifest="'.$this->prop('appcache').'"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" manifest="'.$this->prop('appcache').'"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" manifest="'.$this->prop('appcache').'"> <!--<![endif]-->
EOS;
		}
		else # no appcache
		{
			$html_before .= <<<EOS
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
EOS;
		}
		// head section
		$html_before .= '<head>';
		// load base configuration
		$mjolnir_base = \app\CFS::config('mjolnir/base');

		# --- Relevant to the user experience -------------------------------- #

		// content type
		$html_before .= '<meta http-equiv="content-type" content="'
			. $this->prop('content-type', 'text/html', null)
			. '; charset='.$mjolnir_base['charset'].'">';

		// Make a DNS handshake with a foreign domain, so the connection goes
		// faster when the user eventually needs to access it.
		// eg. //ajax.googleapis.com
		foreach ($this->prop_index('dns-prefetch', []) as $prefetch_domain)
		{
			'<link rel="dns-prefetch" href="'.$prefetch_domain.'">';
		}
		// mobile viewport optimized: h5bp.com/viewport
		$viewport = $this->prop('viewport', null);
		if ($viewport !== null)
		{
			$html_before .= '<meta name="viewport" content="'.$viewport.'">';
		}
		// helps a little with compatibility; unnecesary \w .htaccess
		$html_before .= '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
		// standard favicon path
		if ($this->prop('favicon') === null)
		{
			// check for png version
			if (\app\Env::key('www.path') !== null && \file_exists(\app\Env::key('www.path').'favicon.png'))
			{
				$html_before .= '<link rel="shortcut icon" href="//'.$mjolnir_base['domain'].$mjolnir_base['path'].'favicon.png" type="image/png">';
			}
			else # no png version
			{
				$html_before .= '<link rel="shortcut icon" href="//'.$mjolnir_base['domain'].$mjolnir_base['path'].'favicon.ico" type="image/x-icon">';
			}
		}
		else # predefined path
		{
			$html_before .= '<link rel="shortcut icon" href="'.$this->prop('favicon').'" type="image/x-icon">';
		}
		// title
		$html_before .= '<title>'.$this->prop('title', \app\CFS::config('mjolnir/base')['system']['title'], null).'</title>';
		// add fix for IE
		$html_before .= '<!--[if lt IE 9)><script src="//'.$mjolnir_base['domain'].$mjolnir_base['path'].'media/static/html5shiv.js"></script><![endif)-->';
		// stylesheets
		foreach ($this->props('stylesheet', []) as $style)
		{
			$html_before .= '<link rel="stylesheet" type="'.$style['type'].'" href="'.$style['href'].'">';
		}
		// kill IE6's pop-up-on-mouseover toolbar for images
		$html_before .= '<meta http-equiv="imagetoolbar" content="no">';

		# --- Relevant to the social networks -------------------------------- #

		// facebook og metas
		if ($this->prop('facebookmetas') !== null)
		{
			$html_before .= $this->prop('facebookmetas');
		}

		# --- Relevant to search engine results ------------------------------ #

		$pagedesc = $this->prop('description', null, null);
		if ($pagedesc !== null)
		{
			// note: it is not guranteed search engines will use it; and they
			// won't if the content of the page is nonexistent, or this
			// description is not unique enough over multiple pages.
			$html_before .= '<meta name="description" content="'.$pagedesc.'">';
		}

		// extra garbage: keywords, generator, author
		$keyword_array = $this->prop_index('keywords', null, null);
		if ( ! empty($keyword_array))
		{
			$keywords = '';
			foreach ($keyword_array as $keyword)
			{
				$keywords .= ' '.$keyword;
			}
			$html_before .= '<meta name="keywords" content="'.$keywords.'">';
		}

		$generator = $this->prop('generator', null, null);
		if ($generator !== null)
		{
			$html_before .= '<meta name="generator" content="'.$generator.'">';
		}

		$author = $this->prop('author', null, null);
		if ($author !== null)
		{
			$html_before .= '<meta name="author" content="'.$author.'">';
		}

		# --- Relevant to crawlers ------------------------------------------- #

		// A canonical route is the route by which search engines should
		// identify the current page; ragerdless of what the current url might
		// look like.
		$canonical = $this->prop('canonical', null);
		if ($canonical !== null)
		{
			$html_before .= '<link rel="canonical" href="'.$canonical.'">';
		}

		// sitemap, for search engines.
		// see: http://www.sitemaps.org/protocol.html
		$sitemap = $this->get('sitemap');
		if ($sitemap !== null)
		{
			$html_before .= '<link rel="sitemap" type="application/xml" title="Sitemap" href="'.$sitemap.'">';
		}

		// block search engines from viewing the page
		$crawlers = $this->prop('crawlers', true);
		if ($crawlers)
		{
			$html_before .= '<meta name="robots" content="index, follow" />';
		}
		else # do not allow search engines
		{
			$html_before .= '<meta name="robots" content="noindex" />';
		}

		# --- Feed and callbacks --------------------------------------------- #

		// http://www.rssboard.org/rss-specification
		$rssfeed = $this->prop('rssfeed');
		if ($rssfeed !== null)
		{
			$html_before .= '<link rel="alternate" type="application/rss+xml" title="RSS" href="'.$rssfeed.'">';
		}

		// http://www.atomenabled.org/developers/syndication/
		$atomfeed = $this->prop('atomfeed');
		if ($atomfeed !== null)
		{
			$html_before .= '<link rel="alternate" type="application/atom+xml" title="Atom" href="'.$atomfeed.'">';
		}

		// http://codex.wordpress.org/Introduction_to_Blogging#Pingbacks
		$pingback = $this->prop('pingback');
		if ($pingback !== null)
		{
			$html_before .= '<link rel="pingback" href="'.$pingback.'">';
		}

		# --- Extras --------------------------------------------------------- #

		// see: http://humanstxt.org/
		$humantxt = $this->prop('humanstxt', false, null);
		if ($humantxt !== null)
		{
			$html_before .= '<link type="text/plain" rel="author" href="'.\app\URL::base().'humans.txt">';
		}

		# Pin status (IE9 etc)

		// name to use when pinned
		$application_name = $this->prop('application_name');
		if ($application_name !== null)
		{
			$html_before .= '<meta name="application-name" content="'.$application_name.'">';
		}

		// tooltip to use when pinned
		$application_tooltip = $this->prop('application_tooltip');
		if ($application_tooltip !== null)
		{
			$html_before .= '<meta name="msapplication-tooltip" content="'.$application_tooltip.'">';
		}

		// page to go to when pinned
		$application_starturl = $this->prop('application_starturl');
		if ($application_starturl !== null)
		{
			$html_before .= '<meta name="msapplication-starturl" content="'.$application_starturl.'">';
		}

		$scripts = $this->props('script', null, null);

		if ( ! empty($scripts) && \app\CFS::config('mjolnir/html')['js-loader'] !== null)
		{
			$formats = $this->prop_index('js-loader-formats', ['application/javascript', 'text/javascript']);

			$usable = false;
			foreach ($scripts as $script)
			{
				if (\in_array($script['type'], $formats))
				{
					$usable = true;
					break;
				}
			}

			if ($usable)
			{
				// javascript loader
				$html_before .= '<script type="text/javascript" src="'.\app\CFS::config('mjolnir/html')['js-loader'].'"></script>';
			}
		}

		// head scripts
		foreach ($this->props('startup-script', [], null) as $script)
		{
			$html_before .= '<script type="'.$script['type'].'" src="'.\addslashes($script['src']).'"></script>';
		}

		$extra_markup = $this->props('extra-markup');
		if ( ! empty($extra_markup))
		{
			foreach ($extra_markup as $markup)
			{
				$html_before .= $markup;
			}
		}

		// close head section
		$onunload = $this->prop('onunload', null);
		$html_before .= '</head><body class="'.\implode(' ', $this->prop_index('body-class', [])).'" '.($onunload !== null ? 'onunload="'.$onunload.'"' : '').'>';

		// css switch for more streamline style transitions
		if ($this->prop('javascriptswitch'))
		{
			$html_before .= '<script type="text/javascript">document.body.id = "javascript-enabled";</script>';
		}
		$html_before .= "\n\n";

		return $html_before;
	}

	// ------------------------------------------------------------------------
	// Helpers

	/**
	 * @return string
	 */
	protected function html_after()
	{
		$html_after = "\n\n";
		$scripts = $this->props('script', null, null);

		if ( ! empty($scripts))
		{
			$javascripts = [];
			foreach ($scripts as $script)
			{
				if ($script['type'] === 'application/javascript' || $script['type'] === 'text/javascript' || $script['type'] === 'application/x-javascript')
				{
					$javascripts[] = $script['src'];
				}
				else # other type of script
				{
					$html_after .= '<script type="'.$script['type'].'" src="'.$script['src'].'"></script>';
				}
			}

			if ( ! empty($javascripts))
			{
				if (\app\CFS::config('mjolnir/html')['js-loader'] === null)
				{
					foreach ($javascripts as $script)
					{
						$html_after .= '<script type="application/javascript" src="'.\addslashes($script).'"></script>';
					}
				}
				else # load using loader
				{
					$jsloaderhandler = $this->prop('js-loader-handler', 'yepnope');
					$html_after .= '<script type="text/javascript">'.$jsloaderhandler.'({ load: ['."\n";
					$html_after .= "'".\addslashes(\array_shift($javascripts)).'\'';
					foreach ($javascripts as $script)
					{
						$html_after .= ",\n'".\addslashes($script)."'";
					}
					$html_after .= "\n] });</script>";
				}
			}
		}

		$extra_footer_markup = $this->prop('extra-footer-markup');
		if ( ! empty($extra_footer_markup))
		{
			foreach ($extra_footer_markup as $markup)
			{
				$html_after .= $markup;
			}
		}

		$html_after .= "</body></html>\n";

		return $html_after;
	}

	/**
	 * ...
	 */
	protected function wrap(\mjolnir\types\Channel $channel)
	{
		if (\app\CFS::config('mjolnir/html')['output']['pretty'])
		{
			// format output
			$html = $this->html_before($channel).$channel->get('body', '').$this->html_after();
			$dom = new \DOMDocument();
			$dom->preserveWhiteSpace = true;
			$dom->loadHTML($html);
			$dom->formatOutput = true;

			$channel->set('body', $dom->saveHTML());
		}
		else # no formatting
		{
			$channel->set('body', $this->html_before($channel).$channel->get('body', '').$this->html_after());
		}

	}

} # class
