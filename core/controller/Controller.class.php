<?php

namespace core\controller;

use core\router\Route;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;

class Controller
{

	private Route $route;
	private ReflectionClass $reflectClass;
	private ReflectionMethod $reflectMethod;
	/** @var \ReflectionParameter[] */
	private array $reflectParams;

	public function __construct(ReflectionClass $oReflectClass, ReflectionMethod $oReflectMethod, ReflectionAttribute $oReflectRoute)
	{
		$this->reflectClass = $oReflectClass;
		$this->reflectMethod = $oReflectMethod;
		$this->reflectParams = $oReflectMethod->getParameters();
		$this->route = $oReflectRoute->newInstance();
	}

	/**
	 * Simple accesseur vers la méthode "match" de la route
	 */
	public function routeMatch(array $aRequestUri, string $sRequestMethod): bool
	{
		return $this->route->match($aRequestUri, $sRequestMethod);
	}

	/**
	 * Instancie et appel la méthode du controller en hydratant ses arguments
	 */
	public function run()
	{

		$sClass = $this->reflectClass->getName();
		$sMethod = $this->reflectMethod->getName();
		$aParams = $this->getHydratedParameters();

		// Instantiation du controller
		$oController = new $sClass();

		// $oController->method(prm1: value, prm2: value, ...);
		// Appel de méthode avec des paramètre nommé !
		// PHP 8.0 > All
		$oController->$sMethod(...$aParams);

	}

	/**
	 * Hydratation des paramètres de la méthode du controller
	 * Les paramètre sortent sous forme de tableau associatif
	 * Permet d'avoir des paramètres nommés (arg: value) & variatics (...$args)
	 */
	private function getHydratedParameters()
	{

		$aParameters = [];

		foreach ($this->reflectParams as $oParameter) {

			// Exemple :
			// public function example($arg) {}
			// $oParameter->getName() = "arg"
			$sParameter = $oParameter->getName();

			// Récupère le chunk depuis le paramètre de la méthode "arg"
			$oChunk = $this->route->getChunkFromName($sParameter);

			// Le nom du chunk match avec le nom du paramètre
			if ($oChunk) {
				// Save sa valeur dans le tableau associatif
				$aParameters[$sParameter] = $oChunk->getValue();
			}

		}

		return $aParameters;

	}

}