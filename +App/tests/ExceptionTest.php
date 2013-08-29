<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Exception;

class ExceptionTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Exception'));
	}

	// @todo tests for \mjolnir\foundation\Exception

} # test
