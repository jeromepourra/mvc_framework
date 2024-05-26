<?php

namespace core\database;

use core\database\EQueryDoor;
use core\database\EQueryJoin;
use core\database\EQueryOperator;

class QueryInsert
{
	public string $column;						// Colonne
	public mixed $value;						// Valeur
	public function __construct(string $sColumn, mixed $mValue)
	{
		$this->column = $sColumn;
		$this->value = $mValue;
	}
}

class QueryUpdate
{
	public string $column;						// Colonne
	public mixed $value;						// Valeur
	public function __construct(string $sColumn, mixed $mValue)
	{
		$this->column = $sColumn;
		$this->value = $mValue;
	}
}

class QueryJoin
{
	public EQueryJoin $type;
	public string $table;
	public ?string $alias;
	public mixed $firstValue;
	public EQueryOperator $operator;
	public mixed $secondValue;

	/**
	 * @param string $sTable Table à joindre
	 * @param ?string $sAlias Alias de la table (null si pas d'alias)
	 * @param mixed $mFirstValue Premiere valeur/colonne de condition
	 * @param EQueryOperator $sOperator L'opérateur de condition
	 * @param mixed $mSecondValue Seconde valeur/colonne de condition
	 * @param EQueryJoin $sType Type de jointure
	 */
	public function __construct(string $sTable, ?string $sAlias, mixed $mFirstValue, EQueryOperator $sOperator, mixed $mSecondValue, EQueryJoin $sType)
	{
		$this->type = $sType;
		$this->table = $sTable;
		$this->alias = $sAlias;
		$this->firstValue = $mFirstValue;
		$this->operator = $sOperator;
		$this->secondValue = $mSecondValue;
	}
}

class QueryWhere
{
	public string $column; 						// Colonne de condition
	public EQueryOperator $operator; 			// L'opérateur à appliquer
	public mixed $value; 						// La valeur qui doit correspondre
	public ?EQueryDoor $door; 					// Porte logique
	public function __construct(string $sColumn, EQueryOperator $sOperator, mixed $mValue, ?EQueryDoor $sDoor)
	{
		$this->column = $sColumn;
		$this->operator = $sOperator;
		$this->value = $mValue;
		$this->door = $sDoor;
	}
}

class QueryBinding
{
	public string $value; 						// La valeur a binder
	public int|string $param;					// Le num ou le nom du param a binder
	public function __construct(mixed $mValue, int|string $mParam)
	{
		$this->value = $mValue;
		$this->param = $mParam;
	}
}