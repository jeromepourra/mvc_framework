<?php

namespace core\model;

use core\response\Response;
use core\response\ResponseCode;
use lib\Singleton;
use PDO;
use PDOException;
use PDOStatement;

class Database extends Singleton
{

	public PDO $pdo;
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
			Response::SendCode(ResponseCode::INTERNAL_SERVER_ERROR);
		}
	}

	private function validStatement(): bool
	{
		return $this->statement instanceof PDOStatement;
	}

	public function prepare(string $sQuery): void
	{
		$this->statement = $this->pdo->prepare($sQuery);
		if (!$this->statement) {
			LOGERROR("can't prepare query: %s", $sQuery);
		}
	}

	/**
	 * FIX ME
	 * Pour faire les choses proprement on peut faire une reflection de la class Entity
	 * Pour acceder à ses types de props et faire un bind value du type :)
	 * Et pourquoi pas la lenght
	 */
	public function bind(array $aBind, ): void
	{
		if (!$this->validStatement()) {
			LOGERROR("invalid statement");
		}

		if (!empty($bind)) {
			foreach ($aBind as $name => $value) {
				$this->statement->bindValue($name, $value);
			}
		}
	}

	public function execute(): void
	{
		if (!$this->validStatement()) {
			LOGERROR("invalid statement");
		}

		$bExecuted = $this->statement->execute();
		if (!$bExecuted) {
			LOGERROR("can't execute query: %s", $this->statement->queryString);
		}
	}

	public function fetch(int $nMode = PDO::FETCH_ASSOC): mixed
	{
		if (!$this->validStatement()) {
			LOGERROR("invalid statement");
		}

		$mData = $this->statement->fetch($nMode);
		return $mData;

	}

	public function fetchAll(int $nMode = PDO::FETCH_ASSOC): mixed
	{
		if (!$this->validStatement()) {
			LOGERROR("invalid statement");
		}

		$mData = $this->statement->fetchAll($nMode);
		return $mData;
	}

	/**
	 * @template T
	 * @param class-string<T> $sClass
	 * @return T
	 */
	public function fetchObj(string $sClass)
	{
		if (!$this->validStatement()) {
			LOGERROR("invalid statement");
		}

		$oModed = $this->statement->setFetchMode(PDO::FETCH_CLASS, $sClass); 
		$oData = $this->statement->fetch();
		return $oData;
	}

	/**
	 * @template T
	 * @param class-string<T> $sClass
	 * @return T[]
	 */
	public function fetchAllObj(string $sClass)
	{
		if (!$this->validStatement()) {
			LOGERROR("invalid statement");
		}

		$oModed = $this->statement->setFetchMode(PDO::FETCH_CLASS, $sClass); 
		$oData = $this->statement->fetchAll();
		return $oData;
	}

	private function fetchMode(int $nMode, mixed ...$args): void {
		$bAssigned = $this->statement->setFetchMode($nMode, ...$args);
		if (!$bAssigned) {
			LOGERROR("can't assign fetch mode: %s with args: %s", $nMode, print_r($args, true));
		}
	}

}


class User
{
	public ?int $id = null;
	public ?string $name = null;
	public ?string $password = null;

	public function __construct()
	{
	}
}