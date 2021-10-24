<?php


namespace models;


use core\Db\DbModel;

class Likes extends DbModel
{

	public ?int $id = null;
	public ?int $post = null;
	public ?int $user = null;
	public ?int $status = null;
	public int $type = 0;


	public function attributes(): array
	{
		return [
			'post',
			'user',
			'type',
			'status'
		];
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
	 * the rules should be respect by each child model
	 * @return array
	 */
	public function rules(): array
	{
		// TODO: Implement rules() method.
	}
}
