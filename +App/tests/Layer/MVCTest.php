<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Layer_MVC;

class Layer_MVCTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Layer_MVC'));
	}

	// @todo tests for \mjolnir\foundation\Layer_MVC

} # test
