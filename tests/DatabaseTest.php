<?php

use core\database\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
	public function testConnected(): void
	{
		$this->assertInstanceOf(Database::class, Database());
	}
}