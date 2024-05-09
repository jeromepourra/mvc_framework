<?php

use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase
{
	public function testTrue(): void
	{
		$this->assertTrue(true);
	}

	public function testFalse(): void
	{
		$this->assertFalse(false);
	}
}