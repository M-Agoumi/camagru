<?php


namespace Model;


class EmailToken extends \Simfa\Framework\Db\DbModel
{
	public ?int $entityID = null;
	public ?string $email = '';
	public ?string $token = '';
	public int $used = 0;

	/**
	 * @var string table primary key
	 */
	protected static string $tableName = 'email_token';

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
