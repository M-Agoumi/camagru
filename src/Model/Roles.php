<?php


namespace Model;


class Roles extends \Simfa\Db\DbModel
{
	public ?int $id = null;
	public ?User $user = null;
	public bool $super_admin = false;
	public bool $users = false;
	public bool $posts = false;
	public bool $comments = false;
	public bool $likes = false;
	public bool $promote = false;
	public ?User $updated_by = null;

	/**
	 * @var string table primary key
	 */
	protected static string $primaryKey = 'id';

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
