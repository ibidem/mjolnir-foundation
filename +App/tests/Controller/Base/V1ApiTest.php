<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Controller_Base_V1Api;

class Controller_Base_V1ApiTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Controller_Base_V1Api'));
	}

	// @todo tests for \mjolnir\foundation\Controller_Base_V1Api

} # test
