<?php


namespace Model;

use Simfa\Framework\Db\DbModel;

class Comments extends DbModel
{

	public ?int $id = null;
	public ?int $post = null;
	public ?int $user = null;
	public ?string $content = null;
	public int $status = 0;

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
			'post' => [self::RULE_REQUIRED],
			'user' => [self::RULE_REQUIRED],
			'content' => [self::RULE_REQUIRED]
		];
	}


	/**
	 * @param $id
	 * @return User get the user by the id
	 */
	public function user($id): User
	{
		$user = New User();

		$user->getOneBy($id);

		return $user;
	}
}
