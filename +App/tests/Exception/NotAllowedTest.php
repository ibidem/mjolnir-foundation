<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Exception_NotAllowed;

class Exception_NotAllowedTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Exception_NotAllowed'));
	}

	// @todo tests for \mjolnir\foundation\Exception_NotAllowed

} # test
