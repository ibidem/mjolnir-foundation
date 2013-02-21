<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_MVC extends \app\Instantiatable implements \mjolnir\types\Layer
{
	use \app\Trait_Layer;

	/**
	 * ...
	 */
	function run()
	{
		$channel = $this->channel();

		// we register ourselves in the channel
		$channel->set('layer:mvc', $this);

		$relaynode = $channel->get('relaynode');
		$controller = $relaynode->get('controller');

		// controller can be passed directly as an object or as a string;
		// strings are more efficient and easier to use when dealing with
		// configuration, while objects are more flexible and allow you to have
		// things such as control wrappers that turn virtually any class and
		// method into a controller by implemnting the Processed and Channeled
		// interfaces for them

		if (\is_string($controller))
		{
			$controller = $controller::instance();
		}

		$action = $relaynode->get('action', null);
		if ($action !== null) 
		{
			$action = $relaynode->get('prefix', 'action_').\str_replace('-', '_', $action);
		}
		else # no action
		{
			$action = $relaynode->get('default.action', 'public_index');
		}
		

		// we give the controller access to the channel
		$controller->channel_is($channel);

		// we perform preprocessing
		$controller->preprocess();

		// we then compute the body; all controller actions are expected to
		// return the correct body to facilitate syntax; we simply set the
		// channel body ourselves after the fact
		$response = \call_user_func([$controller, $action]);
		
		if (\is_string($response))
		{
			$channel->set('body', $response);
		}
		else if (\is_a($response, '\mjolnir\types\Renderable')) # Renderable object
		{
			$channel->set('body', $response->render());
		}
		else # misc contents
		{
			$channel->set('body', $response);
		}
		
		// we perform any controller postprocessing; this should happen on the
		// channel mainly; the response is available in the channels body
		// property
		$controller->postprocess();
	}

	/**
	 * ...
	 */
	function recover()
	{
		$relaynode = $this->channel()->get('relaynode');
		$errornode = $relaynode->get('error.relaynode', null);

		if ($errornode === null)
		{
			$errornode = \app\RelayNode::instance()
				->set('controller', \app\Controller_Error::instance())
				->set('action', 'process_error')
				->set('prefix', '');
		}
		else if (\is_string($errornode))
		{
			$errornode = \app\RelayNode::instance($errornode);
		}

		$this->channel()->set('relaynode', $errornode);
	}

} # class
