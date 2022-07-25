<?php


namespace Model;

use Simfa\Framework\Db\DbModel;

class Password_reset extends DbModel
{
	public ?int $entityID = null;
	public ?string $email = null;
	public ?string $token = null;
	public ?int $used = null;

	/**
	 * @var string table primary key
	 */
	protected static string $primaryKey = 'entityID';


	/**
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
			'token' => [self::RULE_REQUIRED]
		];
	}
}
