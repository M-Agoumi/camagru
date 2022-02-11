<?php


namespace Simfa\Model;


use Model\User;

class UserToken extends \Simfa\Framework\Db\DbModel
{
	public ?int $entityID = null;
	public ?User $user = null;
	public ?string $token = null;
	public ?int $used = null;

	protected static string $tableName =  'user_token';

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
