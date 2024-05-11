<?php

namespace lib;

/*
 **************************************************
 ** Singleton [abstract]
 **************************************************
 **
 ** classe abstraire d'instance unique
 ** peut être etendu à n'importe quelle classe de l'app
 ** en appellant la méthode Instance() on renverra toujours
 ** la même instance de l'object enfant
 **
 **************************************************
 */

abstract class Singleton
{
	private static ?self $instance = null;

	/**
	 * récupère ou créé (si non existance)
	 * l'instance unique de la class enfant
	 */
	final public static function Instance(): self
	{
		if (self::$instance === null) {
			$childClass = get_called_class();
			self::$instance = new $childClass;
		}
		return self::$instance;
	}

	/**
	 * pas d'override
	 * pas d'instantiation en dehors d'ici
	 */
	final private function __construct()
	{
	}

}