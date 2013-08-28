<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Channel;

class ChannelTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Channel'));
	}

	// @todo tests for \mjolnir\foundation\Channel

} # test
