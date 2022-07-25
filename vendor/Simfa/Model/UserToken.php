<?php


namespace Simfa\Model;


use Model\User;
use Simfa\Framework\Db\DbModel;

/**
 * @method getToken()
 */
class UserToken extends DbModel
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
		return [];
	}

	/**
	 * @return string[]
	 */
	public function relationships(): array
	{
		return ['user' => User::class];
	}

	/**
	 * @param int $id
	 * @return void
	 */
	public function setUser(int $id)
	{
		/** todo rework this shit, it depends on class it from the app not the framework, which is wrong */
		if ($id !== null)
			$this->user = \Model\User::findOne(['entityID' => $id]);
	}
}
