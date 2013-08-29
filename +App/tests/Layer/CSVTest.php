<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Layer_CSV;

class Layer_CSVTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Layer_CSV'));
	}

	// @todo tests for \mjolnir\foundation\Layer_CSV

} # test
