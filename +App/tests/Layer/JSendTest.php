<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Layer_JSend;

class Layer_JSendTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Layer_JSend'));
	}

	// @todo tests for \mjolnir\foundation\Layer_JSend

} # test
