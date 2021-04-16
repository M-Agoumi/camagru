<?php


namespace models;


use core\Db\DbModel;

class Post extends DbModel
{

	public ?int $id = null;
	public ?string $title = null;
	public ?string $comment = null;
	public ?string $picture = null;
	public ?string $slug = null;
	public ?int $status = null;
	public ?int $author = null;


	/**
	 * @return string
	 */
	public function tableName(): string
	{
		return 'posts';
	}

	/**
	 * @return array
	 */
	public function attributes(): array
	{
		return ['title', 'comment', 'picture', 'slug', 'status', 'author'];
	}

	/**
	 * @return string
	 */
	public function primaryKey(): string
	{
		return 'id';
	}

	/**
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'picture' => [self::RULE_REQUIRED],
		];
	}

}