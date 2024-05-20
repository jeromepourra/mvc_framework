<?php

use PHPUnit\Framework\TestCase;
use src\controller\HomeController;

class ControllerTest extends TestCase
{
	public function testConnected(): void
	{
		Router()->register(HomeController::class);
		$this->assertNull(Router()->load("/", "GET"));
	}
}