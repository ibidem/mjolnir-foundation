<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Layer_Resource;

class Layer_ResourceTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Layer_Resource'));
	}

	// @todo tests for \mjolnir\foundation\Layer_Resource

} # test
