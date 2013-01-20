<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Controller
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Controller_Web extends \app\Instantiatable implements \mjolnir\types\Controller
{
	use \app\Trait_Controller
		{
			preprocess as protected trait_preprocess;
		}

	/**
	 * @var string target
	 */
	protected static $target = null;

	/**
	 * @var mixed
	 */
	protected $context = null;

	/**
	 * Execute before actions.
	 */
	function preprocess()
	{
		$this->trait_preprocess();

		if (static::$target !== null && \app\Server::request_method() === 'GET')
		{
			$this->context = $this->make_context();
		}

		// pre-initialize view (for showing errors)
		$this->themeview();
	}

	/**
	 * @return \app\ThemeView
	 */
	function themeview()
	{
		static $themeview = null;

		if ( ! isset($themeview))
		{
			$themeview = \app\ThemeView::instance()
				->layer($this->layer)
				->context($this->context)
				->control($this);
		}

		return $themeview;
	}

	/**
	 * Index action.
	 */
	function action_index()
	{
		return $this->themeview()
			->target(static::$target)
			->render();
	}

	/**
	 * @return string
	 */
	function method()
	{
		return \app\Server::request_method();
	}

	/**
	 * @return string url
	 */
	function action($action)
	{
		$relay = $this->layer->get_relay();
		return $relay['matcher']->url(array('action' => $action));
	}

	// ------------------------------------------------------------------------
	// Helpers

	/**
	 * @return mixed context
	 */
	protected function make_context()
	{
		$class = '\app\Context_'.\ucfirst(static::$target);
		return $class::instance();
	}

	/**
	 * Shorthand for redirecting the user to another page after completing an
	 * operations.
	 */
	protected function forward($relay, array $params = null, $query = null)
	{
		\app\Server::redirect(\app\URL::href($relay, $params, $query));
	}

} # class
