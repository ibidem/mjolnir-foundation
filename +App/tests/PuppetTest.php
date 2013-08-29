<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Puppet;

class PuppetTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Puppet'));
	}

	// @todo tests for \mjolnir\foundation\Puppet

} # test
