<?php


namespace models;


use core\Db\DbModel;

class Password_reset extends DbModel
{
	public ?int $id = null;
	public ?string $email = null;
	public ?string $token = null;
	public ?int $used = null;

	/**
	 * @return string
	 */
	public function tableName(): string
	{
		return "password_reset";
	}

	/**
	 * @return array
	 */
	public function attributes(): array
	{
		return ['email', 'token', 'used'];
	}

	/**
	 * @return string
	 */
	public function primaryKey(): string
	{
		return 'id';
	}

	/**
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

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