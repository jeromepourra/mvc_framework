<?php

namespace core\database;

use core\database\Query;
use core\database\EQueryOperator;

abstract class Model
{
	protected Query $query; 				// Query builder
	protected string $table; 				// Table associé a l'entité
	protected string $entity; 				// Le nom de class de l'entité

	// L'instantiation se fait toujours ici pas d'override possible
	public final function __construct()
	{

		if (!defined("static::TABLE")) {
			LOGERROR("class: %s must implement public/protected const TABLE", $this::class);
		}

		if (!defined("static::ENTITY")) {
			LOGERROR("class: %s must implement public/protected const ENTITY", $this::class);
		}

		$this->query = new Query();
		$this->table = $this::TABLE;
		$this->entity = $this::ENTITY;

	}

	/**
	 * @return Entity[]
	 */
	public final function findAll(): array
	{
		$oQuery = $this->query
			->select("*")
			->from($this->table);
		$oEntity = Database()
			->prepare($oQuery->getQuery())
			->bind($oQuery->getBindings())
			->execute()
			->fetchAllObj($this->entity);
		return $oEntity;
	}

	public final function findOneById(mixed $sValue): ?Entity
	{
		return $this->findOneBy("id", $sValue);
	}

	public final function findOneBy(string $sField, mixed $sValue): ?Entity
	{
		$oQuery = $this->query
			->select("*")
			->from($this->table)
			->where($sField, EQueryOperator::EQUAL, $sValue);
		$oEntity = Database()
			->prepare($oQuery->getQuery())
			->bind($oQuery->getBindings())
			->execute()
			->fetchObj($this->entity);
		return $oEntity;
	}
}