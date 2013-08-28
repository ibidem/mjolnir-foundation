<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Layer_Theme;

class Layer_ThemeTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Layer_Theme'));
	}

	// @todo tests for \mjolnir\foundation\Layer_Theme

} # test
