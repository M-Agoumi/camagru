<?php

namespace Simfa\Framework\Db\Migration;

class Schema
{
	/**
	 * @var Column[]
	 */
	private array $columns = [];

	public function id(): Schema
	{
		$this->columns[] = new Column([
			'name' => 'entityID',
			'type' => 'int',
			'length' => false,
			'nullable' => false,
			'primary' => true,
			'default' => false
		]);

		return $this;
	}

	public function int($name): Schema
	{
		$this->columns[] = new Column([
			'name' => $name,
			'type' => 'int',
			'length' => false,
			'nullable' => false,
			'primary' => false,
			'default' => false
		]);

		return $this;
	}

	public function smallInt($name): Schema
	{
		$this->columns[] = new Column([
			'name' => $name,
			'type' => 'tinyint',
			'length' => false
		]);

		return $this;
	}

	public function string($name, $length = 255): Schema
	{
		$this->columns[] = new Column([
			'name' => $name,
			'type' => 'string',
			'length' => $length
		]);

		return $this;
	}

	public function text($name):Schema
	{
		$this->columns[] = new Column([
			'name' => $name,
			'type' => 'text',
			'length' => false
		]);

		return $this;
	}

	public function dateTime($name): Schema
	{
		$this->columns[] = new Column([
			'name' => $name,
			'type' => 'dateTime',
			'length' => false
		]);

		return $this;
	}

	public function timestamps()
	{
		$this->columns[] = new Column([
			'name' => 'created_at',
			'type' => 'dateTime',
			'length' => false,
			'default' => 'CURRENT_TIMESTAMP()'
		]);

		$this->columns[] = new Column([
			'name' => 'updated_at',
			'type' => 'dateTime',
			'length' => false,
			'nullable' => true
		]);
	}

	public function nullable(): Schema
	{
		$this->columns[sizeof($this->columns) - 1]->nullable();

		return $this;
	}

	public function default($value): Schema
	{
		$this->columns[sizeof($this->columns) - 1]->default($value);

		return $this;
	}

	public function primary(): Schema
	{
		$this->columns[sizeof($this->columns) - 1]->primary();

		return $this;
	}

	public function length($length): Schema
	{
		$this->columns[sizeof($this->columns) - 1]->length($length);

		return $this;
	}

	public function unique(): Schema
	{
		$this->columns[sizeof($this->columns) - 1]->unique();

		return $this;
	}

	public function getColumns(): array
	{
		return $this->columns;
	}

	public function foreign($column): Column
	{
		$column = new Column(
			['foreign' => $column]
		);
		$this->columns[] = $column;

		return $column;
	}
}
