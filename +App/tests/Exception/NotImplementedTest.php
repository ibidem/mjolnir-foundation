<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Exception_NotImplemented;

class Exception_NotImplementedTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Exception_NotImplemented'));
	}

	// @todo tests for \mjolnir\foundation\Exception_NotImplemented

} # test
