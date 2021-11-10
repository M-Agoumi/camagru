<?php


namespace models;


use core\Db\DbModel;

class ContactUs extends DbModel
{

	public ?int $id = null;
	public ?int $logged = null;
	public ?int $user = null;
	public ?string $email = null;
	public ?string $title = null;
	public ?string $content = null;
	public ?int $parentId = null;
	public ?int $status = null;

	/**
	 * @var string table name in the database
	 */
	protected static string $tableName = "contact_us";

	/**
	 * @var string table primary key
	 */
	protected static string $primaryKey = 'id';

	/**
	 * @inheritDoc
	 */
	public function rules(): array
	{
		return [
			'title' => [self::RULE_REQUIRED],
			'content' => [self::RULE_REQUIRED],
			'email' => [self::RULE_EMAIL]
		];
	}
}
