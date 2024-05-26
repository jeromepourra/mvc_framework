<?php

namespace lib;

use ReflectionClass;
use ReflectionException;

abstract class MagicObject
{

	/**
	 * Attention avec ceci, la propriété de l'instance sera modifié peut importe son accessibilité
	 * I'm PHP and if you try to update public, private or protected i don't care about it :)
	 */
	public function __set(string $sName, mixed $mValue): void
	{
		try {
			$oReflection = new ReflectionClass($this);
			$oProperty = $oReflection->getProperty($sName);
			$oProperty->setValue($this, $mValue);
		} catch (ReflectionException $e) {
			throw $e;
		}
	}

	public function __get(string $sName): mixed
	{
		try {
			$oReflection = new ReflectionClass($this);
			$oProperty = $oReflection->getProperty($sName);
			return $oProperty->getValue($this);
		} catch (ReflectionException $e) {
			throw $e;
		}
	}

	public function __toString(): string
	{
		$aObject = [];
		$oReflection = new ReflectionClass($this);
		$aProperties = $oReflection->getProperties();

		foreach ($aProperties as $oProperty) {
			$aObject[$oProperty->getName()] = [
				'value' => $oProperty->getValue($this),
				'type' => $oProperty->getType(),
			];
		}

		return Dumper($aObject);
	}

}