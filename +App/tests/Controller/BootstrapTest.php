<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Controller_Bootstrap;

class Controller_BootstrapTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Controller_Bootstrap'));
	}

	// @todo tests for \mjolnir\foundation\Controller_Bootstrap

} # test
