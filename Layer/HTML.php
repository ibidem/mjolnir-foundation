<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_HTML extends \app\Instantiatable implements \mjolnir\types\Layer, \mjolnir\types\Meta
{
	use \app\Trait_Layer;
	use \app\Trait_Meta;

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
	function recover ()
	{
		$this->set('crawlers', false);
	}

	/**
	 * ...
	 */
	function reset()
	{
		$this->metadata_is(\app\CFS::config('mjolnir/html'));
	}

	/**
	 * ...
	 */
	protected function wrap(\mjolnir\types\Channel $channel)
	{
		$channel->set('body', $this->html_before($channel).$channel->get('body', '').$this->html_after());
	}

	/**
	 * @return string
	 */
	protected function html_before(\mjolnir\types\Channel $channel)
	{
		$html_before = $this->get('doctype')."\n";
		// appcache manifest
		if ($this->get('appcache') !== null)
		{
			$html_before .= '<html manifest="'.$this->get('appcache').'">';
		}
		else # no appcache
		{
			$html_before .= '<html>';
		}
		// head section
		$html_before .= '<head>';
		// load base configuration
		$mjolnir_base = \app\CFS::config('mjolnir/base');

		# --- Relevant to the user experience -------------------------------- #

		// content type
		$html_before .= '<meta http-equiv="content-type" content="'
			. $channel->get('content-type', 'text/html')
			. '; charset='.$mjolnir_base['charset'].'">';

		// Make a DNS handshake with a foreign domain, so the connection goes
		// faster when the user eventually needs to access it.
		// eg. //ajax.googleapis.com
		foreach ($this->get('prefetch_domains') as $prefetch_domain)
		{
			'<link rel="dns-prefetch" href="'.$prefetch_domain.'">';
		}
		// mobile viewport optimized: h5bp.com/viewport
		$html_before .= '<meta name="viewport" content="'.$this->get('viewport').'">';
		// helps a little with compatibility; unnecesary \w .htaccess
		$html_before .= '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
		// standard favicon path
		if ($this->get('favicon') === null)
		{
			// check for png version
			if (\defined('PUBDIR') && \file_exists(PUBDIR.'favicon.png'))
			{
				$html_before .= '<link rel="shortcut icon" href="//'.$mjolnir_base['domain'].$mjolnir_base['path'].'favicon.png" type="image/png">';
			}
			else #
			{
				$html_before .= '<link rel="shortcut icon" href="//'.$mjolnir_base['domain'].$mjolnir_base['path'].'favicon.ico" type="image/x-icon">';
			}
		}
		else # predefined path
		{
			$html_before .= '<link rel="shortcut icon" href="'.$this->get('favicon').'" type="image/x-icon">';
		}
		// title
		$html_before .= '<title>'.$this->get('title').'</title>';
		// add fix for IE
		$html_before .= '<!--[if lt IE 9)><script src="//'.$mjolnir_base['domain'].$mjolnir_base['path'].'media/static/html5shiv.js"></script><![endif)-->';
		// stylesheets
		foreach ($this->get('stylesheets') as $style)
		{
			$html_before .= '<link rel="stylesheet" type="'.$style['type'].'" href="'.$style['href'].'">';
		}
		// kill IE6's pop-up-on-mouseover toolbar for images
		$html_before .= '<meta http-equiv="imagetoolbar" content="no">';

		# --- Relevant to the social networks -------------------------------- #

		// facebook og metas
		if ($this->get('facebookmetas') !== null)
		{
			$html_before .= $this->get('facebookmetas');
		}

		# --- Relevant to search engine results ------------------------------ #

		if ($this->get('description') !== null)
		{
			// note: it is not guranteed search engines will use it; and they
			// won't if the content of the page is nonexistent, or this
			// description is not unique enough over multiple pages.
			$html_before .= '<meta name="description" content="'.$this->get('description').'">';
		}

		// extra garbage: keywords, generator, author
		$keywords = $this->get('keywords');
		if ( ! empty($keywords))
		{
			$keywords = '';
			foreach ($this->get('keywords') as $keyword)
			{
				$keywords .= ' '.$keyword;
			}
			$html_before .= '<meta name="keywords" content="'.$keywords.'">';
		}
		if ($this->get('generator') !== null)
		{
			$html_before .= '<meta name="generator" content="'.$this->get('generator').'">';
		}
		if ($this->get('author') !== null)
		{
			$html_before .= '<meta name="author" content="'.$this->get('author').'">';
		}

		# --- Relevant to crawlers ------------------------------------------- #

		// A canonical route is the route by which search engines should
		// identify the current page; ragerdless of what the current url might
		// look like.
		if ($this->get('canonical') !== null)
		{
			$html_before .= '<link rel="canonical" href="'.$this->get('canonical').'">';
		}

		// sitemap, for search engines.
		// see: http://www.sitemaps.org/protocol.html
		if ($this->get('sitemap') !== null)
		{
			$html_before .= '<link rel="sitemap" type="application/xml" title="Sitemap" href="'.$this->get('sitemap').'">';
		}

		// block search engines from viewing the page
		if ($this->get('crawlers'))
		{
			$html_before .= '<meta name="robots" content="index, follow" />';
		}
		else # do not allow search engines
		{
			$html_before .= '<meta name="robots" content="noindex" />';
		}

		# --- Feed and callbacks --------------------------------------------- #

		// http://www.rssboard.org/rss-specification
		if ($this->get('rssfeed') !== null)
		{
			$html_before .= '<link rel="alternate" type="application/rss+xml" title="RSS" href="'.$this->get('rssfeed').'">';
		}

		// http://www.atomenabled.org/developers/syndication/
		if ($this->get('atomfeed') !== null)
		{
			$html_before .= '<link rel="alternate" type="application/atom+xml" title="Atom" href="'.$this->get('atomfeed').'">';
		}

		// http://codex.wordpress.org/Introduction_to_Blogging#Pingbacks
		if ($this->get('pingback') !== null)
		{
			$html_before .= '<link rel="pingback" href="'.$this->get('pingback').'">';
		}

		# --- Extras --------------------------------------------------------- #

		// see: http://humanstxt.org/
		if ($this->get('humanstxt'))
		{
			$html_before .= '<link type="text/plain" rel="author" href="'.$mjolnir_base['base_url'].'humans.txt">';
		}

		# Pin status (IE9 etc)

		// name to use when pinned
		if ($this->get('application_name') !== null)
		{
			$html_before .= '<meta name="application-name" content="'.$this->get('application_name').'">';
		}

		// tooltip to use when pinned
		if ($this->get('application_tooltip') !== null)
		{
			$html_before .= '<meta name="msapplication-tooltip" content="'.$this->get('application_tooltip').'">';
		}

		// page to go to when pinned
		if ($this->get('application_starturl') !== null)
		{
			$html_before .= '<meta name="msapplication-starturl" content="'.$this->get('application_starturl').'">';
		}

		$scripts = $this->get('scripts');
		if ( ! empty($scripts))
		{
			// javascript loader
			$html_before .= '<script type="text/javascript" src="'.\app\CFS::config('mjolnir/html')['js-loader'].'"></script>';
		}

		$head_scripts = $this->get('head_scripts');
		foreach ($head_scripts as $script)
		{
			$html_before .= '<script type="text/javascript" src="'.\addslashes($script).'"></script>';
		}

		$extra_markup = $this->get('extra_markup');
		if ( ! empty($extra_markup))
		{
			foreach ($extra_markup as $markup)
			{
				$html_before .= $markup;
			}
		}

		// close head section
		$html_before .= '</head><body class="'.\implode(' ', $this->get('body_classes')).'">';
		// css switch for more streamline style transitions
		if ($this->get('javascript_switch'))
		{
			$html_before .= '<script type="text/javascript">document.body.id = "javascript-enabled";</script>';
		}
		$html_before .= "\n\n";

		return $html_before;
	}

	/**
	 * Closing html.
	 *
	 * @return string
	 */
	protected function html_after()
	{
		$html_after = "\n\n";
		$scripts = $this->get('scripts');
		if ( ! empty($scripts))
		{
			$html_after .= '<script type="text/javascript">yepnope({ load: [';
			$html_after .= '\''.\addslashes(\array_shift($scripts)).'\'';
			foreach ($scripts as $script)
			{
				$html_after .= ', \''.\addslashes($script).'\'';
			}
			$html_after .= ') });</script>';
		}

		$extra_footer_markup = $this->get('extra_footer_markup');
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

} # class
