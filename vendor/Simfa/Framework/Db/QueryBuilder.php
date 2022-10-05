<?php

namespace Simfa\Framework\Db;

use PDOStatement;
use Simfa\Framework\Application;

class QueryBuilder
{

	/**
	 * @var Database
	 */
	private Database $db;

	/**
	 * @var string
	 */
	private string $model;

	/**
	 * @var string
	 */
	private string $selector = '*';

	/**
	 * @var array
	 */
	private array $criteria = [];

	/**
	 * @var string|null
	 */
	private ?string $condition = '';

	/**
	 * @var array
	 */
	private array $searchCriteria = [];

	/**
	 * @var int
	 */
	private int $limit = 0;

	/**
	 * @var int
	 */
	private int $offset = 0;

	/**
	 * @var string
	 */
	private string $order = '';

	/**
	 * @var bool
	 */
	private bool $desc = false;

	/**
	 * @var string
	 */
	private string $primaryKey;

	/**
	 * @param string $modelTable
	 * @param string $primaryKey
	 */
	public function __construct(string $modelTable, string $primaryKey)
	{
		$this->model = $modelTable;
		$this->primaryKey = $primaryKey;
		$this->db = Application::$APP->db;
	}

	/**
	 * @param array|string $selector
	 * @return $this
	 */
	public function select(array|string $selector = '*'): static
	{
		if (is_array($selector))
			$this->selector = implode(', ', $selector);
		else
			$this->selector = $selector;

		return $this;
	}

	/**
	 * @param string $column
	 * @param string $criteria
	 * @param string|null $value
	 * @param string|null $valueFn
	 * @return $this
	 */
	public function where(string $column, string $criteria, ?string $value, ?string $valueFn = null): static
	{
		$this->criteria[] = $this->condition . '`' . $column . '` ' . $criteria . ' :' . $column;

		if (!$valueFn)
			$this->searchCriteria[':' . $column] = $value;
		else {
			if ($valueFn = '%')
				$this->searchCriteria[':' . $column] = '%' . $value . '%';
			else
				$this->searchCriteria[':' . $column] = $valueFn . '(' . $value . ')';
		}


		$this->condition = '';

		return $this;
	}

	/**
	 * @return $this
	 */
	public function and(): static
	{
		$this->condition = 'AND ';

		return $this;
	}

	/**
	 * @return $this
	 */
	public function or(): static
	{
		$this->condition = 'OR ';
		return $this;
	}

	/**
	 * @param int $limit
	 * @return $this
	 */
	public function limit(int $limit): static
	{
		$this->limit = $limit;

		return $this;
	}

	/**
	 * @param int $offset
	 * @return $this
	 */
	public function offset(int $offset): static
	{
		$this->offset = $offset;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function desc(): static
	{
		$this->desc = true;

		return $this;
	}

	/**
	 * @param string $orderBy
	 * @return $this
	 */
	public function order(string $orderBy): static
	{
		$this->order = $orderBy;

		return $this;
	}

	/**
	 * @return bool|array
	 */
	public function get(): bool|array
	{
		$query = 'SELECT ' . $this->selector;
		$query .= ' FROM ' . $this->model;
		if (!empty($this->criteria)){
			$query .= ' WHERE';
			foreach ($this->criteria as $where) {
				$query .= ' ' . $where;
			}
		}
		if ($this->order)
			$query .= ' ORDER BY ' . $this->order;
		if ($this->desc)
			$query .= $this->order ? ' DESC' : ' ORDER BY ' . $this->primaryKey . ' DESC';
		if ($this->limit)
			$query .= ' LIMIT ' . $this->limit;
		if ($this->offset)
			$query .= ' OFFSET ' . $this->offset;


		$stmt = $this->prepare($query);

		foreach ($this->searchCriteria as $key => $value) {
			$stmt->bindValue("$key", $value);
		}

		$stmt->execute();

		return $stmt->fetchAll(2);
	}

	/**
	 * @param $sql
	 * @return bool|PDOStatement
	 */
	private function prepare($sql): bool|PDOStatement
	{
		return $this->db->pdo->prepare($sql);
	}
}
