<?php


namespace Model;


use Simfa\Framework\Db\DbModel;

/**
 * @method setUser(User $user)
 * @method getUser():User
 * @method setUsers(int $int)
 * @method setComments(int $int)
 * @method setLikes(int $int)
 * @method setPosts(int $int)
 * @method setPromote(int $int)
 */
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
