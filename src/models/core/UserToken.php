<?php


namespace models\core;


use models\User;

class UserToken extends \core\Db\DbModel
{
	public ?int $id = null;
	public ?User $user = null;
	public ?string $token = null;
	public ?int $used = null;

	protected static string $tableName =  'user_token';

	public function attributes(): array
	{
		return ['user', 'token', 'used'];
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
		// TODO: Implement rules() method.
		return [];
	}

	public function relationships(): array
	{
		return ['user' => User::class];
	}

	public function setUser(int $id)
	{
		if ($id !== null)
			$this->user = User::findOne(['id' => $id]);
	}
}
