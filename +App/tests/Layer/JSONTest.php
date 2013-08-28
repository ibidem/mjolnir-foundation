<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Layer_JSON;

class Layer_JSONTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Layer_JSON'));
	}

	// @todo tests for \mjolnir\foundation\Layer_JSON

} # test
