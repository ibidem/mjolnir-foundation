<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\URLRoute;

class URLRouteTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\URLRoute'));
	}

	// @todo tests for \mjolnir\foundation\URLRoute

} # test
