<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Notice extends \app\Instantiatable implements \mjolnir\types\Meta, \app\Trait_Savable
{
	use \app\Trait_Meta;
	use \app\Trait_Savable;
	
	/**
	 * @var array registed notices on the system
	 */
	protected static $notices = null;
	
	/**
	 * Creates a notice for the user. You can grab the notice(s) via 
	 * `Notice::all()`.
	 * 
	 * Don't forget to save the notice.
	 * 
	 * @return \app\Notice
	 */
	static function make($body)
	{
		$instance = static::instance();
		
		$instance->set('body', $body);
		
		return $instance;
	}
	
	/**
	 * Returns the array of notices; once returned the notices no longer exist
	 * in the system.
	 * 
	 * @return array notices
	 */
	static function all()
	{
		static::init();
		
		$notices = static::$notices;
		
		static::$notices = [];
		\app\Session::set('mjolnir:notices', static::$notices);
		
		return $notices;
	}
	
	/**
	 * Save the notice.
	 * 
	 * @return static $this
	 */
	function save()
	{
		$this->saved = true;
		
		static::init();
		static::$notices[] = $this;
		\app\Session::set('mjolnir:notices', static::$notices);
		
		return $this;
	}
	
	/**
	 * Notices that don't reach the user can cause a lot of trouble.
	 */
	function __destruct()
	{
		if ( ! $this->saved)
		{
			\mjolnir\log('Bug', 'You have a unsaved notice with the message: '.$this->get('body', null), 'Bugs');
		}
	}
	
	/**
	 * Initialize the notices.
	 */
	protected static function init()
	{
		if (static::$notices === null)
		{
			static::$notices = \app\Session::get('mjolnir:notices', []);
		}
	}
	

} # class
