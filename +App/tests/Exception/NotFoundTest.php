<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Exception_NotFound;

class Exception_NotFoundTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Exception_NotFound'));
	}

	// @todo tests for \mjolnir\foundation\Exception_NotFound

} # test
