<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Controller_Error;

class Controller_ErrorTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Controller_Error'));
	}

	// @todo tests for \mjolnir\foundation\Controller_Error

} # test
