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
	private ?string     $foreign;
	private ?string     $references = null;
	private ?string     $referencesTable = null;
	private ?string     $update = null;
	private ?string     $delete = null;

	public function __construct(array $params)
	{
		$this->foreign = $params['foreign'] ?? null;
		if (!$this->foreign) {
			$this->name     = $params['name'] ?? '';
			$this->type     = $params['type'] ?? 'string';
			$this->length   = $params['length'] ?? '';
			$this->nullable = $params['nullable'] ?? false;
			$this->primary  = $params['primary'] ?? false;
			$this->default  = $params['default'] ?? '';
			$this->unique   = $params['unique'] ?? false;
			$this->foreign  = null;
		}
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
		if (!$this->foreign)
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
			$sql .= strlen($this->default) ? ' DEFAULT ' . $this->default : '';
		} else {
			$sql = 'FOREIGN KEY (' . $this->foreign . ') REFERENCES ' . $this->referencesTable;
			$sql .= ' (' . $this->references . ')';
			if ($this->update)
				$sql .= ' ON UPDATE ' . $this->update;
			if ($this->delete)
				$sql .= ' ON DELETE ' . $this->delete;
		}

		return $sql;
	}

	public function references(string $references): Column
	{
		$this->references = $references;

		return $this;
	}

	public function on(string $table): Column
	{
		$this->referencesTable = $table;

		return $this;
	}

	public function onUpdate(string $action): Column
	{
		$this->update = $action;

		return $this;
	}

	public function onDelete(string $action): Column
	{
		$this->delete = $action;

		return $this;
	}
}
