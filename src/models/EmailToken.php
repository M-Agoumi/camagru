<?php


namespace models;


class EmailToken extends \core\Db\DbModel
{
	public ?int $id = null;
	public ?string $email = '';
	public ?string $token = '';
	public int $used = 0;

	public function tableName(): string
	{
		return 'emailToken';
	}

	public function attributes(): array
	{
		return ['email', 'token', 'used'];
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
		return [
			'email' => [self::RULE_EMAIL, self::RULE_REQUIRED],
			'token' => [self::RULE_REQUIRED]
		];
	}
}
