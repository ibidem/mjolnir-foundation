<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Application;

class ApplicationTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Application'));
	}

	// @todo tests for \mjolnir\foundation\Application

} # test
