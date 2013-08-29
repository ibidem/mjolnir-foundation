<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Layer_HTML;

class Layer_HTMLTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Layer_HTML'));
	}

	// @todo tests for \mjolnir\foundation\Layer_HTML

} # test
