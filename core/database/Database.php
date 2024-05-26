<?php

namespace core\database;

use core\response\Response;
use core\response\ResponseCode;
use lib\Singleton;
use PDO;
use PDOException;
use PDOStatement;

class Database extends Singleton
{

	private PDO $pdo;
	private ?PDOStatement $statement;

	protected function __construct()
	{
		$sDsn = "mysql:";
		$sDsn .= "host=" . Config()->get("DATABASE_HOST") . ";";
		$sDsn .= "port=" . Config()->get("DATABASE_PORT") . ";";
		$sDsn .= "dbname=" . Config()->get("DATABASE_NAME") . ";";
		$sDsn .= "charset=" . Config()->get("DATABASE_CHARSET");

		try {
			$this->pdo = new PDO($sDsn, Config()->get("DATABASE_USER_NAME"), Config()->get("DATABASE_USER_PASSWORD"));
		} catch (PDOException $th) {
			throw new PDOException("can't connect to database");
		}
	}

	public function lastInsertId(): int
	{
		return $this->pdo->lastInsertId();
	}

	public function prepare(string $sQuery): self
	{
		$this->statement = $this->pdo->prepare($sQuery);
		if (!$this->statement) {
			LOGERROR("can't prepare query: %s", $sQuery);
		}
		return $this;
	}

	/**
	 * @param QueryBinding[] $aBind
	 */
	public function bind(array $aBind): self
	{
		if (!empty($aBind)) {
			foreach ($aBind as $oBind) {
				$this->statement->bindValue($oBind->param, $oBind->value);
			}
		}
		return $this;
	}

	public function execute(): self
	{
		$bExecuted = $this->statement->execute();
		if (!$bExecuted) {
			LOGERROR("can't execute query: %s", $this->statement->queryString);
		}
		return $this;
	}

	public function fetch(int $nMode = PDO::FETCH_ASSOC): mixed
	{
		$this->fetchMode($nMode);
		$mData = $this->statement->fetch();
		return $this->getResult($mData);
	}

	public function fetchAll(int $nMode = PDO::FETCH_ASSOC): mixed
	{
		$this->fetchMode($nMode);
		$mData = $this->statement->fetchAll();
		return $this->getResult($mData);
	}

	/**
	 * @template T
	 * @param class-string<T> $sClass
	 * @return ?T
	 */
	public function fetchObj(string $sClass)
	{
		$this->fetchMode(PDO::FETCH_CLASS, $sClass);
		$oData = $this->statement->fetch();
		return $this->getResult($oData);
	}

	/**
	 * @template T
	 * @param class-string<T> $sClass
	 * @return ?T[]
	 */
	public function fetchAllObj(string $sClass)
	{
		$this->fetchMode(PDO::FETCH_CLASS, $sClass);
		$oData = $this->statement->fetchAll();
		return $this->getResult($oData);
	}

	private function fetchMode(int $nMode, mixed ...$args): void {
		$bAssigned = $this->statement->setFetchMode($nMode, ...$args);
		if (!$bAssigned) {
			LOGERROR("can't assign fetch mode: %s with args: %s", $nMode, print_r($args, true));
		}
	}

	/**
	 * @template T
	 * @param T $mData
	 * @return ?T
	 */
	private function getResult(object|array|false $oData) {
		if (!$oData) {
			return null; // override false result
		}
		return $oData;
	}

}