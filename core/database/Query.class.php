<?php

namespace core\database;
use EQueryDoor;
use EQueryOperator;

// Pas envie de faire 50.000 fichiers pour chaques type
// pour permettre la possibilité de faire des autoloads
require App()->mkPath("core/database/QueryEnums.php");
require App()->mkPath("core/database/QueryConstructors.php");

class Query {

	private Database $db;

	private bool $useSelect = false;				// SELECT
	private bool $useInsert = false;				// INSERT INTO
	private bool $useDelete = false;				// DELETE
	private bool $useSet = false;					// UPDATE
	private bool $useDistinct = false;				// DISTINCT

    private ?string $table = null;					// Table SQL

	/** @var string[] */
    private array $select = [];						// Fields de retour

	/** @var QueryWhere[] */
    private array $where = [];						// Liste des conditions

	/** @var QueryBinding[] */
	private array $bindings = [];					// Liste des valeurs à bind sur la query
	private int $bindIndex = 0;						// Index courant de la valeur a bind

	/** @var string[] */
	private array $queryList = [];					// Liste des query a join()
    
    public function __construct() {
		$this->db = Database();
    }

    public function select(string ...$sColumns): self {
		$this->select = array_merge($this->select, $sColumns);
		$this->useSelect = true;
        return $this;
    }

	public function distinct(): self {
        $this->useDistinct = true;
        return $this;
    }

	public function from(string $sTable): self {
		$this->table = $sTable;
		return $this;
	}

	public function where(string $sColumn, EQueryOperator $sOperator = EQueryOperator::EQUAL, mixed $mValue = null, ?EQueryDoor $sDoor = null): self {
		$this->where[] = new QueryWhere($sColumn, $sOperator, $mValue, $sDoor);
		if ($mValue !== null) {
			$this->bindIndex++; // Index du bind param au moment de construire la requête préparée
			$this->bindings[] = new QueryBinding($mValue, $this->bindIndex);
		}
		return $this;
	}

	public function andWhere(string $sColumn, EQueryOperator $sOperator = EQueryOperator::EQUAL, mixed $mValue = null): self {
		return $this->where($sColumn, $sOperator, $mValue, EQueryDoor::AND);
	}

	public function orWhere(string $sColumn, EQueryOperator $sOperator = EQueryOperator::EQUAL, mixed $mValue = null): self {
		return $this->where($sColumn, $sOperator, $mValue, EQueryDoor::OR);
	}

	private function buildSelect(): void {
		if (!empty($this->useSelect)) {
			$this->queryList[] = "SELECT";
			if ($this->useDistinct) {
				$this->queryList[] = "DISTINCT";
			}
			if (!empty($this->select)) {
				$this->queryList[] = "*";
			} else {
				$this->queryList[] = implode(",", $this->select);
			}
		}
	}

	private function buildFrom(): void {
		$this->queryList[] = "FROM";
		$this->queryList[] = $this->table;
	}
     
    private function buildWhere(): void {
        if ($this->where) {
			$this->queryList[] = "WHERE";
			foreach ($this->where as $oWhere) {
				if ($oWhere->door !== null) {
					$this->queryList[] = $oWhere->door->value; // Porte logique
				}
				$this->queryList[] = $oWhere->column; // Colonne de condition
				$this->queryList[] = $oWhere->operator->value; // Opérateur de condition
				if ($oWhere->value !== null) {
					$this->queryList[] = "?"; // Valeur a binder
				}
			}
        }
    }

	private function build(): ?string {
		$this->buildSelect();
		$this->buildFrom();
		$this->buildWhere();
		if (empty($this->queryList)) {
			return null;
		}
		return join(" ", $this->queryList);
	}

    public function getQuery(): ?string {
		return $this->build();
    }

	/**
	 * @return QueryBinding[]
	 */
    public function getBindings(): array {
		return $this->bindings;
    }

}