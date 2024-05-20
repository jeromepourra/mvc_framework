<?php

namespace core\database;

use core\database\EQueryDoor;
use core\database\EQueryJoin;
use core\database\EQueryOperator;

class QueryInsert {
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
	public EQueryJoin $type; 					// Type de jointure
	public string $table;						// Table à joindre
	public mixed $firstValue;					// Valeur de condition 1
	public EQueryOperator $operator; 			// L'opérateur à appliquer
	public mixed $secondValue;					// Valeur de condition 2
	public function __construct(string $sTable, mixed $mFirstValue, EQueryOperator $sOperator, mixed $mSecondValue, EQueryJoin $sType)
	{
		$this->type = $sType;
		$this->table = $sTable;
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