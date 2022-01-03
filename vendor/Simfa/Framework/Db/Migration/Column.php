<?php

namespace Simfa\Framework\Db\Migration;

class Column
{

	protected string    $name;
	protected string    $type;
	protected string    $length;
	protected bool      $nullable;
	protected bool      $primary;
	protected string    $default;
	protected bool      $unique;
	private array       $typeKeyword = [
		'dateTime' => 'timestamp',
		'string' => 'varchar',
		'smallInt' => 'tinyint'
	];

	public function __construct(array $params)
	{
		$this->name     = $params['name'] ?? '';
		$this->type     = $params['type'] ?? 'string';
		$this->length   = $params['length'] ?? '';
		$this->nullable = $params['nullable'] ?? false;
		$this->primary  = $params['primary'] ?? false;
		$this->default  = $params['default'] ?? '';
		$this->unique   = $params['unique'] ?? false;
	}

	public function nullable()
	{
		$this->nullable = true;
	}

	public function default(string $value)
	{
		$this->default = $value;
	}

	public function primary()
	{
		$this->primary = true;
	}

	public function length($length)
	{
		$this->length = $length;
	}

	public function unique()
	{
		$this->unique = true;
	}

	private function getType()
	{
		return $this->typeKeyword[$this->type] ?? $this->type;
	}

	public function toString(): string
	{
		/** column name */
		$sql = "`" . $this->name . "`";
		/** column type */
		$sql .= ' ' . $this->getType();
		/** column size */
		$sql .= $this->length ? '(' . $this->length . ')' : '';
		/** column nullable */
		$sql .= $this->nullable ? ' NULL' : ' NOT NULL';
		/** column unique */
		$sql .= $this->unique ? ' UNIQUE' : '';
		/** column primary */
		$sql .= $this->primary ? ' PRIMARY KEY AUTO_INCREMENT' : '';
		/** column default */
		$sql .= $this->default ? ' DEFAULT ' . $this->default : '';

		return $sql;
	}
}
