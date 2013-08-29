<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Layer_HTTP;

class Layer_HTTPTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Layer_HTTP'));
	}

	// @todo tests for \mjolnir\foundation\Layer_HTTP

} # test
