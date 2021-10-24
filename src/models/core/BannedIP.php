<?php


namespace models\core;


class BannedIP extends \core\Db\DbModel
{
	public ?int $id = null;
	public ?string $address = null;
	public ?int $status = null;
	public ?string $comment = null;

	protected static string $tableName = 'banned_ip';

	public function attributes(): array
	{
		return ['address', 'status', 'comment'];
	}

	public function primaryKey(): string
	{
		return 'id';
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @inheritDoc
	 */
	public function rules(): array
	{
		return [];
	}
}
