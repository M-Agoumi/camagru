<?php


namespace models;


class Roles extends \core\Db\DbModel
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


	public function tableName(): string
	{
		return "roles";
	}

	public function attributes(): array
	{
		return ['user', 'super_admin', 'users', 'posts', 'comments', 'likes', 'promote', 'updated_by'];
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
		return [];
	}

	public function relationships(): array
	{
		return ['user' => User::class, 'updated_by' => User::class];
	}
}
