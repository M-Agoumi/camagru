<?php


namespace models;


use core\Db\DbModel;

class Comments extends DbModel
{

	public ?int $id = null;
	public ?int $post = null;
	public ?int $user = null;
	public ?string $content = null;
	public int $status = 0;

	public function attributes(): array
	{
		return ['post', 'user', 'content', 'status'];
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
			'post' => [self::RULE_REQUIRED],
			'user' => [self::RULE_REQUIRED],
			'content' => [self::RULE_REQUIRED]
		];
	}

	public function user($id): User
	{
		$user = New User();

		$user->getOneBy($id);

		return $user;
	}
}
