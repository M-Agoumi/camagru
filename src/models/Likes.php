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

	/**
	 * @var string table primary key
	 */
	protected static string $primaryKey = 'id';

	/**
	 * the rules should be respect by each child model
	 * @return array
	 */
	public function rules(): array
	{
		// TODO: Implement rules() method.
	}
}
