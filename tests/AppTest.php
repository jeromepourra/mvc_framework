<?php

use lib\App;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
	public function testApp(): void
	{
		$this->assertInstanceOf(App::class, App());
	}

	public function testMkWebPath(): void
	{
		$this->assertEquals("./www/path/to/file", App()->mkWebPath("path/to/file"));
	}

	public function testMkPublicPath(): void
	{
		$this->assertEquals("./www/public/path/to/file", App()->mkPublicPath("path/to/file"));
	}

	public function testMkPublicUrl(): void
	{
		$this->assertEquals("./public/path/to/file", App()->mkPublicUrl("path/to/file"));
	}

	public function testMkTemplatePath(): void
	{
		$this->assertEquals("./www/templates/path/to/file", App()->mkTemplatePath("path/to/file"));
	}

}