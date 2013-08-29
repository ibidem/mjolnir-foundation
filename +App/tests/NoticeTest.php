<?php namespace mjolnir\foundation\tests;

use \mjolnir\foundation\Notice;

class NoticeTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\foundation\Notice'));
	}

	// @todo tests for \mjolnir\foundation\Notice

} # test
