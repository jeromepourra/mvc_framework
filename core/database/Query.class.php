<?php

namespace core\database;

use core\database\EQueryDoor;
use core\database\EQueryJoin;
use core\database\EQueryOperator;
use Exception;

// Pas envie de faire 50.000 fichiers pour chaques type
// pour permettre la possibilité de faire des autoloads
require App()->mkPath("core/database/QueryEnums.php");
require App()->mkPath("core/database/QueryConstructors.php");

class Query
{

	private Database $db;

	private bool $useSelect = false;				// SELECT
	private bool $useInsert = false;				// INSERT INTO
	private bool $useUpdate = false;				// UPDATE
	private bool $useDelete = false;				// DELETE
	private bool $useDistinct = false;				// DISTINCT

	private ?string $fromTable = null;				// Table de sélection
	private ?string $intoTable = null;				// Table d'insertion

	/** @var string[] */
	private array $select = [];						// Fields de retour

	/** @var QueryInsert[] */
	private array $insert = [];						// Fields d'insertion

	/** @var QueryUpdate[] */
	private array $update = [];						// Fields de mise à jour

	/** @var QueryJoin[] */
	private array $joinList = []; 					// Liste des jointures

	/** @var QueryWhere[] */
	private array $whereList = [];					// Liste des conditions

	private ?int $limit = null;						// Limit le nombre de retour

	private ?int $offset = null;					// Décale le curseur de retour

	/** @var QueryBinding[] */
	private array $bindings = [];					// Liste des valeurs à bind sur la query
	private int $bindIndex = 0;						// Index courant de la valeur a bind

	/** @var string[] */
	private array $queryList = [];					// Liste des query a join()

	public function __construct()
	{
		$this->db = Database();
	}

	// BASE
	// ====

	/**
	 * Reset les types (statements) de requête
	 */
	private function clearUsed()
	{
		$this->useSelect = false;
		$this->useInsert = false;
		$this->useUpdate = false;
		$this->useDelete = false;
	}

	public function select(string ...$sColumns): self
	{
		$this->clearUsed(); // Reset les types de requête
		$this->select = array_merge($this->select, $sColumns);
		$this->useSelect = true;
		return $this;
	}

	public function insert(): self
	{
		$this->clearUsed(); // Reset les types de requête
		$this->useInsert = true;
		return $this;
	}

	public function update(): self
	{
		$this->clearUsed(); // Reset les types de requête
		$this->useUpdate = true;
		return $this;
	}

	public function distinct(): self
	{
		$this->useDistinct = true;
		return $this;
	}

	// (new Query())->select()->from('table')
	public function from(string $sTable): self
	{
		$this->fromTable = $sTable;
		return $this;
	}

	// Simple alias à la méthode from()
	// (new Query())->insert()->into('table')
	public function into(string $sTable): self
	{
		$this->intoTable = $sTable;
		return $this;
	}

	// JOINTURES
	// =========

	public function join(string $sTable, mixed $mFirstValue, EQueryOperator $sOperator, mixed $mSecondValue, EQueryJoin $sType = EQueryJoin::INNER): self
	{
		$this->joinList[] = new QueryJoin($sTable, $mFirstValue, $sOperator, $mSecondValue, $sType);
		// Build un tableau avec les deux valeurs à bind, puis parcours
		foreach ([$mFirstValue, $mSecondValue] as $mValue) {
			$this->bindIndex++; // Index du bind param au moment de construire la requête préparée
			$this->bindings[] = new QueryBinding($mValue, $this->bindIndex);
		}
		return $this;
	}

	public function leftJoin(string $sTable, mixed $mFirstValue, EQueryOperator $sOperator, mixed $mSecondValue): self
	{
		return $this->join($sTable, $mFirstValue, $sOperator, $mSecondValue, EQueryJoin::LEFT);
	}

	public function rightJoin(string $sTable, string $mFirstValue, EQueryOperator $sOperator, string $mSecondValue): self
	{
		return $this->join($sTable, $mFirstValue, $sOperator, $mSecondValue, EQueryJoin::RIGHT);
	}

	// CONDITIONS
	// ==========

	public function where(string $sColumn, EQueryOperator $sOperator = EQueryOperator::EQUAL, mixed $mValue = null, ?EQueryDoor $sDoor = null): self
	{
		$this->whereList[] = new QueryWhere($sColumn, $sOperator, $mValue, $sDoor);
		if ($mValue !== null) {
			$this->bindIndex++; // Index du bind param au moment de construire la requête préparée
			$this->bindings[] = new QueryBinding($mValue, $this->bindIndex);
		}
		return $this;
	}

	public function andWhere(string $sColumn, EQueryOperator $sOperator = EQueryOperator::EQUAL, mixed $mValue = null): self
	{
		if (count($this->whereList) == 0) {
			throw new Exception("You must use where() before andWhere()");
		}
		return $this->where($sColumn, $sOperator, $mValue, EQueryDoor::AND );
	}

	public function orWhere(string $sColumn, EQueryOperator $sOperator = EQueryOperator::EQUAL, mixed $mValue = null): self
	{
		if (count($this->whereList) == 0) {
			throw new Exception("You must use where() before orWhere()");
		}
		return $this->where($sColumn, $sOperator, $mValue, EQueryDoor::OR );
	}

	// LIMIT
	// =====

	public function limit(int $nLimit): self
	{
		if ($nLimit > 0) {
			$this->limit = $nLimit;
			return $this;
		} else {
			throw new Exception("Limit must be greater than 0");
		}
	}

	// OFFSET
	// ======

	public function offset(int $nOffset): self
	{
		if ($nOffset > 0) {
			$this->offset = $nOffset;
			return $this;
		} else {
			throw new Exception("Offset must be greater than 0");
		}
	}

	// INSERT
	// ======

	public function insertField(string $sColumn, mixed $mValue): self
	{
		$this->insert[] = new QueryInsert($sColumn, $mValue);
		$this->bindIndex++; // Index du bind param au moment de construire la requête préparée
		$this->bindings[] = new QueryBinding($mValue, $this->bindIndex);
		return $this;
	}

	// BUILDERS
	// ========

	private function buildSelect(): void
	{
		$this->queryList[] = EQueryStatement::SELECT->value;

		if ($this->useDistinct) {
			$this->queryList[] = EQueryClause::DISTINCT->value;
		}

		if (empty($this->select)) {
			$this->queryList[] = "*";
		} else {
			$this->queryList[] = implode(",", $this->select);
		}

		$this->buildFrom();
	}

	private function buildInsert(): void
	{
		$this->queryList[] = EQueryStatement::INSERT->value;
		$this->buildInto();

		$this->queryList[] = "(" . implode(",", array_map(function ($oInsert) {
			return $oInsert->column;
		}, $this->insert)) . ")";

		$this->queryList[] = EQueryClause::VALUES->value;

		$this->queryList[] = "(" . implode(",", array_map(function ($oInsert) {
			return "?";
		}, $this->insert)) . ")";
	}

	private function buildFrom(): void
	{
		if ($this->fromTable !== null) {
			$this->queryList[] = EQueryClause::FROM->value;
			$this->queryList[] = $this->fromTable;
		}
	}

	private function buildInto(): void
	{
		if ($this->intoTable !== null) {
			$this->queryList[] = EQueryClause::INTO->value;
			$this->queryList[] = $this->intoTable;
		}
	}


	private function buildJoin(): void
	{
		if ($this->joinList) {
			foreach ($this->joinList as $oJoin) {
				$this->queryList[] = $oJoin->type->value;
				$this->queryList[] = EQueryClause::JOIN->value;
				$this->queryList[] = $oJoin->table;
				$this->queryList[] = EQueryClause::ON->value;
				$this->queryList[] = $oJoin->firstValue;
				$this->queryList[] = $oJoin->operator->value;
				$this->queryList[] = $oJoin->secondValue;
			}
		}
	}

	private function buildWhere(): void
	{
		if ($this->whereList) {
			$this->queryList[] = EQueryClause::WHERE->value;
			foreach ($this->whereList as $oWhere) {
				if ($oWhere->door !== null) {
					$this->queryList[] = $oWhere->door->value;
				}
				$this->queryList[] = $oWhere->column;
				$this->queryList[] = $oWhere->operator->value;
				if ($oWhere->value !== null) {
					$this->queryList[] = "?";
				}
			}
		}
	}

	private function buildLimit(): void
	{
		if ($this->limit) {
			$this->queryList[] = EQueryClause::LIMIT->value;
			$this->queryList[] = $this->limit;
		}
	}

	private function buildOffset(): void
	{
		if ($this->offset) {
			$this->queryList[] = EQueryClause::OFFSET->value;
			$this->queryList[] = $this->offset;
		}
	}

	private function build(): ?string
	{
		if ($this->useSelect) {
			$this->buildSelect();
		}

		if ($this->useInsert) {
			$this->buildInsert();
		}

		$this->buildJoin();
		$this->buildWhere();
		$this->buildLimit();
		$this->buildOffset();

		if (empty($this->queryList)) {
			return null;
		}

		return join(" ", $this->queryList);
	}

	// FINAL GETTER
	// ============

	public function getQuery(): ?string
	{
		return $this->build();
	}

	/**
	 * @return QueryBinding[]
	 */
	public function getBindings(): array
	{
		return $this->bindings;
	}

}