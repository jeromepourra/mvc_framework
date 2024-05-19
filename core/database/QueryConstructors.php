<?php

namespace core\database;
use EQueryDoor;
use EQueryOperator;

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