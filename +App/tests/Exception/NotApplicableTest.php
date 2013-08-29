<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Exception_NotApplicable;

class Exception_NotApplicableTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Exception_NotApplicable'));
	}

	// @todo tests for \mjolnir\foundation\Exception_NotApplicable

} # test
