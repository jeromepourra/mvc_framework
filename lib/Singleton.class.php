<?php

namespace lib;

/*
 **************************************************
 ** Singleton [abstract]
 **************************************************
 **
 ** classe abstraite d'instance unique
 ** peut être etendu à n'importe quelle classe de l'app
 ** en appellant la méthode Instance() on renverra toujours
 ** la même instance de l'object enfant
 **
 **************************************************
 */

abstract class Singleton
{
	private static array $instance = [];

	/**
	 * récupère ou créé (si non existance)
	 * l'instance unique de la class enfant
	 */
	final public static function Instance(): self
	{
		$sChild = get_called_class();
		if (!array_key_exists($sChild, self::$instance)) {
			self::$instance[$sChild] = new $sChild;
		}
		return self::$instance[$sChild];
	}

	/**
	 * pas d'instantiation en dehors d'ici ou de la class enfant
	 */
	protected function __construct()
	{
	}

}