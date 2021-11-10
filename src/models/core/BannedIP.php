<?php


namespace models\core;


class BannedIP extends \core\Db\DbModel
{
	public ?int $id = null;
	public ?string $address = null;
	public ?int $status = null;
	public ?string $comment = null;

	protected static string $tableName = 'banned_ip';

	protected static string $primaryKey = 'id';

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
