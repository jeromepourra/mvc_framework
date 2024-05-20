<?php

use lib\App;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
	public function testApp(): void
	{
		$this->assertInstanceOf(App::class, App());
	}

	public function testBuildRootPath(): void
	{
		App()->buildRootPath("base/dir");
		$this->assertEquals("./../../", App()->mkPath());
	}

	public function testBuildRootUrl(): void
	{
		$_SERVER['REQUEST_URI'] = "/abc/def";
		App()->buildRootUrl();
		$this->assertEquals("./../../", App()->mkUrl());
	}

	public function testMkWebPath(): void
	{
		App()->buildRootPath("base/dir");
		$this->assertEquals("./../../www/path/to/file", App()->mkWebPath("path/to/file"));
	}

	public function testMkPublicPath(): void
	{
		App()->buildRootPath("base/dir");
		$this->assertEquals("./../../www/public/path/to/file", App()->mkPublicPath("path/to/file"));
	}

	public function testMkPublicUrl(): void
	{
		$_SERVER['REQUEST_URI'] = "/abc/def";
		App()->buildRootUrl();
		$this->assertEquals("./../../public/path/to/file", App()->mkPublicUrl("path/to/file"));
	}

	public function testMkTemplatePath(): void
	{
		App()->buildRootPath("base/dir");
		$this->assertEquals("./../../www/templates/path/to/file", App()->mkTemplatePath("path/to/file"));
	}

}