<?php

use PHPUnit\Framework\TestCase;
use core\database\Query;
use core\database\EQueryJoin;
use core\database\EQueryOperator;

class QueryTest extends TestCase
{
	public function testSelect(): void
	{
		$query = new Query();
		$query->select('column1', 'column2');
		$expected = "SELECT column1,column2";
		$this->assertEquals($expected, $query->getQuery());
	}

	public function testDistinct(): void
	{
		$query = new Query();
		$query->select('column1')->distinct();
		$expected = "SELECT DISTINCT column1";
		$this->assertEquals($expected, $query->getQuery());
	}

	public function testJoin(): void
	{
		$query = new Query();
		$query->join('table2', 'column1', EQueryOperator::EQUAL, 'column2', EQueryJoin::INNER);
		$expected = "INNER JOIN table2 ON column1 = column2";
		$this->assertEquals($expected, $query->getQuery());
	}

	public function testLeftJoin(): void
	{
		$query = new Query();
		$query->leftJoin('table2', 'column1', EQueryOperator::EQUAL, 'column2');
		$expected = "LEFT OUTER JOIN table2 ON column1 = column2";
		$this->assertEquals($expected, $query->getQuery());
	}

	public function testRightJoin(): void
	{
		$query = new Query();
		$query->rightJoin('table2', 'column1', EQueryOperator::EQUAL, 'column2');
		$expected = "RIGHT OUTER JOIN table2 ON column1 = column2";
		$this->assertEquals($expected, $query->getQuery());
	}

	public function testWhere(): void
	{
		$query = new Query();
		$query->select('column')
			->from('table')
			->where('column', EQueryOperator::EQUAL, 'value');

		$expectedQuery = 'SELECT column FROM table WHERE column = ?';
		$this->assertEquals($expectedQuery, $query->getQuery());
	}

	public function testAndWhere(): void
	{
		$query = new Query();
		$query->select('column')
			->from('table')
			->where('column1', EQueryOperator::EQUAL, 'value1')
			->andWhere('column2', EQueryOperator::EQUAL, 'value2');

		$expectedQuery = 'SELECT column FROM table WHERE column1 = ? AND column2 = ?';
		$this->assertEquals($expectedQuery, $query->getQuery());
	}

	public function testAndWhereException(): void
	{
		$this->expectException(Exception::class);

		$query = new Query();
		$query->select('column')
			->from('table')
			->andWhere('column1', EQueryOperator::EQUAL, 'value1');
	}

	public function testOrWhere(): void
	{
		$query = new Query();
		$query->select('column')
			->from('table')
			->where('column1', EQueryOperator::EQUAL, 'value1')
			->orWhere('column2', EQueryOperator::EQUAL, 'value2');

		$expectedQuery = 'SELECT column FROM table WHERE column1 = ? OR column2 = ?';
		$this->assertEquals($expectedQuery, $query->getQuery());
	}

	public function testOrWhereException(): void
	{
		$this->expectException(Exception::class);

		$query = new Query();
		$query->select('column')
			->from('table')
			->orWhere('column1', EQueryOperator::EQUAL, 'value1');
	}

	public function testLimit(): void
	{
		$query = new Query();
		$query->select('column')
			->from('table')
			->limit(10);

		$expectedQuery = 'SELECT column FROM table LIMIT 10';
		$this->assertEquals($expectedQuery, $query->getQuery());
	}

	public function testLimitException(): void
	{
		$this->expectException(Exception::class);

		$query = new Query();
		$query->select('column')
			->from('table')
			->limit(0);
	}

	public function testOffset(): void
	{
		$query = new Query();
		$query->select('column')
			->from('table')
			->offset(10);

		$expectedQuery = 'SELECT column FROM table OFFSET 10';
		$this->assertEquals($expectedQuery, $query->getQuery());
	}

	public function testOffsetException(): void
	{
		$this->expectException(Exception::class);

		$query = new Query();
		$query->select('column')
			->from('table')
			->offset(0);
	}

	public function testFullQuery(): void
	{
		$query = new Query();
		$query->select('column1', 'column2')
			->from('table1')
			->join('table2', 'column1', EQueryOperator::EQUAL, 'column2', EQueryJoin::INNER)
			->where('column3', EQueryOperator::EQUAL, 'value')
			->andWhere('column4', EQueryOperator::EQUAL, 'value')
			->orWhere('column5', EQueryOperator::EQUAL, 'value')
			->limit(10)
			->offset(3);

		$expectedQuery = 'SELECT column1,column2 FROM table1 INNER JOIN table2 ON column1 = column2 WHERE column3 = ? AND column4 = ? OR column5 = ? LIMIT 10 OFFSET 3';
		$this->assertEquals($expectedQuery, $query->getQuery());
	}

	public function testInsert() {
		$query = new Query();
		$query->insert()
		->into('table')
		->insertField('column1', 'value1')
		->insertField('column2', 'value2');

		$expectedQuery = 'INSERT INTO table (column1,column2) VALUES (?,?)';
		$this->assertEquals($expectedQuery, $query->getQuery());
	}

	public function testInsertWithWhere() {
		$query = new Query();
		$query->insert()
		->into('table')
		->insertField('column1', 'value1')
		->insertField('column2', 'value2')
		->where('column3', EQueryOperator::EQUAL, 'value3');

		$expectedQuery = 'INSERT INTO table (column1,column2) VALUES (?,?) WHERE column3 = ?';
		$this->assertEquals($expectedQuery, $query->getQuery());
	}

	public function testInsertWithJoinAndWhereAndOr() {
		$query = new Query();
		$query->insert()
		->into('table')
		->insertField('column1', 'value1')
		->insertField('column2', 'value2')
		->join('table2', 'column1', EQueryOperator::EQUAL, 'column2', EQueryJoin::INNER)
		->where('column3', EQueryOperator::EQUAL, 'value3')
		->andWhere('column4', EQueryOperator::EQUAL, 'value4')
		->orWhere('column5', EQueryOperator::EQUAL, 'value5');

		$expectedQuery = 'INSERT INTO table (column1,column2) VALUES (?,?) INNER JOIN table2 ON column1 = column2 WHERE column3 = ? AND column4 = ? OR column5 = ?';
		$this->assertEquals($expectedQuery, $query->getQuery());
	}

	public function testUpdate() {
		$query = new Query();
		$query->update()
		->from('table')
		->updateField('column1', 'value1')
		->updateField('column2', 'value2');

		$expectedQuery = 'UPDATE table SET column1 = ?,column2 = ?';
		$this->assertEquals($expectedQuery, $query->getQuery());
	}

	public function testUpdateWhere() {
		$query = new Query();
		$query->update()
		->from('table')
		->updateField('column1', 'value1')
		->updateField('column2', 'value2')
		->where('column3', EQueryOperator::EQUAL, 'value3');

		$expectedQuery = 'UPDATE table SET column1 = ?,column2 = ? WHERE column3 = ?';
		$this->assertEquals($expectedQuery, $query->getQuery());
	}

	public function testUpdateJoinWhereAndOr() {
		$query = new Query();
		$query->update()
		->from('table')
		->updateField('column1', 'value1')
		->updateField('column2', 'value2')
		->updateField('column3', 'value3')
		->updateField('column4', 'value4')
		->updateField('column5', 'value5')
		->join('table2', 'column1', EQueryOperator::EQUAL, 'column2', EQueryJoin::INNER)
		->where('column3', EQueryOperator::EQUAL, 'value3')
		->andWhere('column4', EQueryOperator::EQUAL, 'value4')
		->orWhere('column5', EQueryOperator::EQUAL, 'value5');

		$expectedQuery = 'UPDATE table SET column1 = ?,column2 = ?,column3 = ?,column4 = ?,column5 = ? INNER JOIN table2 ON column1 = column2 WHERE column3 = ? AND column4 = ? OR column5 = ?';
		$this->assertEquals($expectedQuery, $query->getQuery());
	}

}