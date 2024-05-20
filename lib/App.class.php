<?php

namespace lib;

class App extends Singleton
{
	private string $urlRoot;
	private string $docRoot;

	/**
	 * Construit le chemin racine relatif depuis le current working directory
	 * C://users/me/project_root/current/dir -> ./../../ = C://users/me/project_root
	 */
	public function buildRootPath(string $baseDir)
	{
		$cwd = $this->normalisePathSeparator(getcwd());
		$this->docRoot = $this->buildRoot($baseDir, $cwd);
	}

	/**
	 * Construit le chemin racine relatif depuis l'URI de la requête
	 * http://site.xyz/abc/def -> ./../../ = http://site.xyz
	 */
	public function buildRootUrl()
	{
		if (isset($_SERVER['REQUEST_URI'])) {
			$this->urlRoot = $this->buildRoot("", $_SERVER['REQUEST_URI']);
		} else {
			$this->urlRoot = $this->buildRoot("", "/");
		}
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

	/**
	 * Construit un chemin relatif vers la racine du projet
	 * 
	 * Compare la différence des paths entre la racine et le current working directory (cwd)
	 * Remonte le fil jusqu'à la racine à partir du cwd
	 */
	private function buildRoot(string $baseDir, string $cwd): string
	{

		// Root: 	C://
		// Cwd: 	C://www/xxx

		$pathDiff = str_replace($baseDir, "", $cwd); // www/xxx
		$pathDiffList = explode("/", $pathDiff); // ["www", "xxx"]
		$maker = function (string $path, string $item): string {
			if (!empty ($item)) {
				$path .= "../"; // remplace item par ../
			}
			return $path;
		};
		return array_reduce($pathDiffList, $maker, "./"); // ./../..
		
	}

	private function buildPath(string $root, string $path = ""): string
	{
		if (empty($path)) {
			return $root;
		} else {
			return $root . $path;
		}
	}

	private function normalisePathSeparator(string $path) {
		return str_replace(DIRECTORY_SEPARATOR, "/", $path);
	}

}