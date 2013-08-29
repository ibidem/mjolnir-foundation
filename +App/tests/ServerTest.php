<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Server;

class ServerTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Server'));
	}

	// @todo tests for \mjolnir\foundation\Server

} # test
