<?php


namespace Model;


use Simfa\Framework\Db\DbModel;

class Role extends DbModel
{
	public ?int $entityID = null;
	public ?User $user = null;
	public bool $super_admin = false;
	public bool $users = false;
	public bool $posts = false;
	public bool $comments = false;
	public bool $likes = false;
	public bool $promote = false;
	public ?User $updated_by = null;


	/**
	 * @inheritDoc
	 */
	public function rules(): array
	{
		return [];
	}

	public function relationships(): array
	{
		return ['user' => User::class, 'updated_by' => User::class];
	}
}
