<?php

namespace lib;
use core\controller\Controller;

class Router extends Singleton
{

	private const DICTIONARY = [
		'index.php' => [
			'c' => "IndexController",
			'p' => "index.php"
		],
	];

	public function loadController(string $sUrl): Controller {

		if (!$this->inDictonary($sUrl)) {
			LOGERROR("url: %s does not exists in dictionary", $sUrl);
		}

		$aInfos = self::DICTIONARY[$sUrl];

		// le nom de la class du controller à instancier
		$sControllerClass = $aInfos['c'];

		// si ['p'] n'est pas défini, le path du controller sera l'url
		$sControllerPath = isset($aInfos['p']) ? $aInfos['p'] : $sUrl;

		$sControllerPath = App()->mkWebPath($sUrl);

		if (!file_exists($sControllerPath)) {
			LOGERROR("controller file: %s does not exists", $sControllerPath);
		}

		// charge le fichier du controller
		require $sControllerPath;

		if (!class_exists($sControllerClass)) {
			LOGERROR("class: %s does not exists in file: %s", $sControllerClass, $sControllerPath);
		}

		// retourne l'instantiation du controller
		return new $sControllerClass();

	}

	public function redirect(string $sUrl, int $nCode = 200): void
	{
		header("Location: " . App()->mkUrl($sUrl), true, $nCode);
		die;
	}

	private function inDictonary(string $sUrl) {
		if (array_key_exists($sUrl, self::DICTIONARY)) {
			return true;
		}
		return false;
	}

}