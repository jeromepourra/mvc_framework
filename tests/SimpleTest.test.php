<?php

use PHPUnit\Framework\TestCase;

final class SimpleTest extends TestCase
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