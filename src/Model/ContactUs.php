<?php


namespace Model;

use Simfa\Framework\Db\DbModel;

class ContactUs extends DbModel
{

	protected ?int $id = null;
	protected ?int $logged = null;
	protected ?int $user = null;
	protected ?string $email = null;
	protected ?string $title = null;
	protected ?string $content = null;
	protected ?int $parentId = null;
	protected ?int $status = null;

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
