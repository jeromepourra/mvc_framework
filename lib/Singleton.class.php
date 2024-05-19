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
	private static array $instances = [];

	/**
	 * récupère ou créé (si non existance)
	 * l'instance unique de la class enfant
	 */
	final public static function Instance(mixed ...$args): static
	{
		$sChild = get_called_class();
		if (!array_key_exists($sChild, self::$instances)) {
			self::$instances[$sChild] = new $sChild(...$args);
		}
		return self::$instances[$sChild];
	}

	/**
	 * pas d'instantiation en dehors d'ici
	 */
	private function __construct()
	{
	}

}