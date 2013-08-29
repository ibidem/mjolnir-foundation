<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Router;

class RouterTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Router'));
	}

	// @todo tests for \mjolnir\foundation\Router

} # test
