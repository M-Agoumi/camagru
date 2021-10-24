<?php


namespace models;


class EmailToken extends \core\Db\DbModel
{
	public ?int $id = null;
	public ?string $email = '';
	public ?string $token = '';
	public int $used = 0;

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
			'email' => [self::RULE_EMAIL, self::RULE_REQUIRED],
			'token' => [self::RULE_REQUIRED]
		];
	}
}
