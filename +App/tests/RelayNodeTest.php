<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\RelayNode;

class RelayNodeTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\RelayNode'));
	}

	// @todo tests for \mjolnir\foundation\RelayNode

} # test
