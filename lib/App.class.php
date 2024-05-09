<?php

namespace lib;

class App
{

	private static ?App $instance = null;
	
	private string $urlRoot;
	private string $docRoot;

	public static function Instance()
	{
		if (self::$instance === null) {
			self::$instance = new App();
		}
		return self::$instance;
	}

	private function __construct()
	{
	}

	public function redirect(string $path, int $code = 200): void
	{
		header("Location: " . App()->mkUrl($path));
		die;
	}

	public function buildRootPath(string $baseDir)
	{
		$this->docRoot = $this->buildRoot($baseDir);
	}

	public function buildRootUrl(string $baseDir)
	{
		$this->urlRoot = $this->buildRoot($baseDir);
	}

	public function mkPath(string $path = ""): string
	{
		return $this->buildPath($this->docRoot, $path);
	}

	public function mkUrl(string $path = ""): string
	{
		return $this->buildPath($this->urlRoot, $path);
	}

	public function mkWebPath(string $path = ""): string
	{
		return $this->buildPath($this->docRoot . "www/", $path);
	}

	public function mkPublicPath(string $path = ""): string
	{
		return $this->buildPath($this->docRoot . "www/public/", $path);
	}

	public function mkPublicUrl(string $path = ""): string
	{
		return $this->buildPath($this->urlRoot . "public/", $path);
	}

	public function mkTemplatePath(string $path = ""): string
	{
		return $this->buildPath($this->docRoot . "www/templates/", $path);
	}

	private function buildRoot(string $baseDir): string
	{
		$pathDiff = str_replace($baseDir, "", getcwd());
		$pathDiffList = explode(DIRECTORY_SEPARATOR, $pathDiff);
		$maker = function (string $path, string $item): string {
			if (!empty ($item)) {
				$path .= "../";
			}
			return $path;
		};
		return array_reduce($pathDiffList, $maker, "./");
	}

	private function buildPath(string $root, string $path = ""): string
	{
		if (empty($path)) {
			return $root;
		} else {
			return $root . $path;
		}
	}

}