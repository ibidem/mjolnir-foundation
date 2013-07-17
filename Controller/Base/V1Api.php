<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Controller
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Controller_Base_V1Api extends \app\Puppet implements \mjolnir\types\Controller
{
	use \app\Trait_Controller;

	/**
	 * @return array
	 */
	function api_index()
	{
		$method = \app\Server::request_method();

		switch ($method)
		{
			case 'GET':
				if (isset($_GET['auditor']))
				{
					return $this->collection()->auditor()->export();
				}
				else # normal GET request
				{
					return $this->get($_GET);
				}

			case 'POST':
				return $this->post($this->payload());

			case 'PUT':
				return $this->put($this->payload());

			case 'PATCH':
				return $this->patch($this->payload());

			case 'DELETE':
				return $this->delete($this->payload());

			default:
				throw new \app\Exception_NotApplicable('Unsuported Request Type');
		}
	}

	/**
	 * @return array
	 */
	function get($conf)
	{
		throw new \app\Exception_NotImplemented();
	}

	/**
	 * @return array
	 */
	function put($req)
	{
		return $this->patch($req);
	}

	/**
	 * @return array
	 */
	function patch($req)
	{
		throw new \app\Exception_NotImplemented();
	}

	/**
	 * @return array
	 */
	function post($req)
	{
		throw new \app\Exception_NotImplemented();
	}

	/**
	 * @return array
	 */
	function delete($req)
	{
		throw new \app\Exception_NotImplemented();
	}

	/**
	 * Retrieve payload. If input is provided, it must be valid json, otherwise
	 * an exception will be thrown.
	 *
	 * @return array|null
	 */
	protected function payload()
	{
		$input = \file_get_contents('php://input');

		if (empty($input))
		{
			return null;
		}

		$payload = \json_decode($input, true);

		// were we able to decode the payload?
		if ($payload !== null)
		{
			return $payload;
		}
		else # failed to decode
		{
			throw new \app\Exception_NotApplicable('Invalid JSON payload passed.');
		}
	}

	/**
	 * @return \mjolnir\types\MarionetteCollection
	 */
	protected function collection()
	{
		$name = \ucfirst(static::camelsingular());
		$class = "\app\\{$name}Collection";
		return $class::instance();
	}

	/**
	 * @return \mjolnir\types\MarionetteModel
	 */
	protected function model()
	{
		$name = \ucfirst(static::camelsingular());
		$class = "\app\\{$name}Model";
		return $class::instance();
	}

} # class
