<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'mjolnir\foundation'


/**
 * @method \app\Application recover_exceptions()
 * @method \app\Application throw_exceptions()
 * @method \app\Application addmetarenderer($key, $metarenderer)
 * @method \app\Application injectmetarenderers(array $metarenderers = null)
 * @method \app\Application channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\Application channel_is($channel = null)
 */
class Application extends \mjolnir\foundation\Application
{
	/** @return \app\Application */
	static function instance() { return parent::instance(); }
	/** @return \app\Application */
	static function stack($args) { return parent::stack($args); }
}

/**
 * @method \app\Channel status_is($status)
 * @method \app\Channel add_preprocessor($name, $processor)
 * @method \app\Channel add_postprocessor($name, $processor)
 * @method \app\Channel preprocess()
 * @method \app\Channel postprocess()
 * @method \app\Channel set($name, $value)
 * @method \app\Channel add($name, $value)
 * @method \app\Channel metadata_is(array $metadata = null)
 */
class Channel extends \mjolnir\foundation\Channel
{
	/** @return \app\Channel */
	static function instance() { return parent::instance(); }
}

class Controller_Base_V1Api extends \mjolnir\foundation\Controller_Base_V1Api
{
	/** @return \app\Controller_Base_V1Api */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Controller_Error add_preprocessor($name, $processor)
 * @method \app\Controller_Error add_postprocessor($name, $processor)
 * @method \app\Controller_Error preprocess()
 * @method \app\Controller_Error postprocess()
 * @method \app\Controller_Error channel_is($channel = null)
 * @method \app\Channel channel()
 */
class Controller_Error extends \mjolnir\foundation\Controller_Error
{
	/** @return \app\Controller_Error */
	static function instance() { return parent::instance(); }
}

class Exception_NotAllowed extends \mjolnir\foundation\Exception_NotAllowed
{
}

class Exception_NotApplicable extends \mjolnir\foundation\Exception_NotApplicable
{
}

class Exception_NotFound extends \mjolnir\foundation\Exception_NotFound
{
}

class Exception_NotImplemented extends \mjolnir\foundation\Exception_NotImplemented
{
}

class Exception extends \mjolnir\foundation\Exception
{
}

/**
 * @method \app\Layer_CSV set($name, $value)
 * @method \app\Layer_CSV add($name, $value)
 * @method \app\Layer_CSV metadata_is(array $metadata = null)
 * @method \app\Layer_CSV channel_is($channel = null)
 * @method \app\Channel channel()
 */
class Layer_CSV extends \mjolnir\foundation\Layer_CSV
{
	/** @return \app\Layer_CSV */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Layer_HTML set($name, $value)
 * @method \app\Layer_HTML add($name, $value)
 * @method \app\Layer_HTML metadata_is(array $metadata = null)
 * @method \app\Layer_HTML channel_is($channel = null)
 * @method \app\Channel channel()
 */
class Layer_HTML extends \mjolnir\foundation\Layer_HTML
{
	/** @return \app\Layer_HTML */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Layer_HTTP set($name, $value)
 * @method \app\Layer_HTTP add($name, $value)
 * @method \app\Layer_HTTP metadata_is(array $metadata = null)
 * @method \app\Layer_HTTP channel_is($channel = null)
 * @method \app\Channel channel()
 */
class Layer_HTTP extends \mjolnir\foundation\Layer_HTTP
{
	/** @return \app\Layer_HTTP */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Layer_JSON set($name, $value)
 * @method \app\Layer_JSON add($name, $value)
 * @method \app\Layer_JSON metadata_is(array $metadata = null)
 * @method \app\Layer_JSON channel_is($channel = null)
 * @method \app\Channel channel()
 */
class Layer_JSON extends \mjolnir\foundation\Layer_JSON
{
	/** @return \app\Layer_JSON */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Layer_JSend set($name, $value)
 * @method \app\Layer_JSend add($name, $value)
 * @method \app\Layer_JSend metadata_is(array $metadata = null)
 * @method \app\Layer_JSend channel_is($channel = null)
 * @method \app\Channel channel()
 */
class Layer_JSend extends \mjolnir\foundation\Layer_JSend
{
	/** @return \app\Layer_JSend */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Layer_MVC set($name, $value)
 * @method \app\Layer_MVC add($name, $value)
 * @method \app\Layer_MVC metadata_is(array $metadata = null)
 * @method \app\Layer_MVC channel_is($channel = null)
 * @method \app\Channel channel()
 */
class Layer_MVC extends \mjolnir\foundation\Layer_MVC
{
	/** @return \app\Layer_MVC */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Layer_Resource set($name, $value)
 * @method \app\Layer_Resource add($name, $value)
 * @method \app\Layer_Resource metadata_is(array $metadata = null)
 * @method \app\Layer_Resource channel_is($channel = null)
 * @method \app\Channel channel()
 */
class Layer_Resource extends \mjolnir\foundation\Layer_Resource
{
	/** @return \app\Layer_Resource */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Layer_Theme set($name, $value)
 * @method \app\Layer_Theme add($name, $value)
 * @method \app\Layer_Theme metadata_is(array $metadata = null)
 * @method \app\Layer_Theme channel_is($channel = null)
 * @method \app\Channel channel()
 */
class Layer_Theme extends \mjolnir\foundation\Layer_Theme
{
	/** @return \app\Layer_Theme */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Notice save()
 * @method \app\Notice addmetarenderer($key, $metarenderer)
 * @method \app\Notice injectmetarenderers(array $metarenderers = null)
 * @method \app\Notice set($name, $value)
 * @method \app\Notice add($name, $value)
 * @method \app\Notice metadata_is(array $metadata = null)
 * @method \app\Notice nosave()
 */
class Notice extends \mjolnir\foundation\Notice
{
	/** @return \app\Notice */
	static function make($body) { return parent::make($body); }
	/** @return \app\Notice */
	static function instance() { return parent::instance(); }
}

class Puppet extends \mjolnir\foundation\Puppet
{
	/** @return \app\Puppet */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\RelayNode set($name, $value)
 * @method \app\RelayNode add($name, $value)
 * @method \app\RelayNode metadata_is(array $metadata = null)
 */
class RelayNode extends \mjolnir\foundation\RelayNode
{
	/** @return \app\RelayNode */
	static function instance(array $source = null) { return parent::instance($source); }
}

class Router extends \mjolnir\foundation\Router
{
	/** @return \app\Linkable */
	static function relay($key) { return parent::relay($key); }
}

class Server extends \mjolnir\foundation\Server
{
}

/**
 * @method \app\URLRoute urlpattern($pattern, array $parameter_regex = null)
 * @method \app\URLRoute urlbase_is($urlbase)
 * @method \app\URLRoute set($name, $value)
 * @method \app\URLRoute add($name, $value)
 * @method \app\URLRoute metadata_is(array $metadata = null)
 */
class URLRoute extends \mjolnir\foundation\URLRoute
{
	/** @return \app\URLRoute */
	static function instance() { return parent::instance(); }
}
