<?php
namespace core\router;

use core\controller\Controller;
use core\response\Response;
use core\response\ResponseCode;
use lib\Singleton;
use ReflectionClass;

class Router extends Singleton
{

	/** @var Controller[] */
	private array $controllers = [];

	/**
	 * Enregistre un controller
	 */
	public function register(string $sControllerClass): void
	{
		$this->reflect($sControllerClass);
	}

	/**
	 * Ajoute dans la liste des enregistrement, toutes les méthodes d'un controller
	 * qui possèdent l'attribut #[Route()]
	 */
	private function reflect(string $sClass): void
	{
		$oReflectClass = new ReflectionClass($sClass);
		$aReflectMethods = $oReflectClass->getMethods();
		$aControllers = [];

		foreach ($aReflectMethods as $oReflectMethod) {

			// Récupère les attributs correspondant à la class Route
			$aReflectAttributes = $oReflectMethod->getAttributes(Route::class);

			if (count($aReflectAttributes) === 1) {
				$oReflectAttribute = $aReflectAttributes[0];
				// Instancie le controller avec sa reflection
				$oController = new Controller($oReflectClass, $oReflectMethod, $oReflectAttribute);
				// Ajoute le controller à la liste des enregistrements
				$this->addController($sClass, $oController);
			}

		}

	}

	public function unregister(string $sControllerClass): bool
	{
		// FIX ME
		return true;
	}

	public function load(string $sRequestUri, string $sRequestMethod): void
	{
		$aRequestUri = explode("/", $sRequestUri);

		foreach ($this->controllers as $sClass => $aControllers) {

			foreach($aControllers as $oController) {

				if ($oController->routeMatch($aRequestUri, $sRequestMethod)) {
					$oController->run();
					return;
				};

			}

		}

		Response::SendCode(ResponseCode::NOT_FOUND);

	}

	private function addController(string $sClass, Controller $oController) {
		
		if (!array_key_exists($sClass, $this->controllers)) {
			$this->controllers[$sClass] = [];
		}

		array_push($this->controllers[$sClass], $oController);

	}

}