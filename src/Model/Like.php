<?php


namespace Model;

use Simfa\Framework\Db\DbModel;

/**
 * @method getType()
 * @method setType(int $type)
 */
class Like extends DbModel
{

	public ?int $entityID = null;
	public ?int $post = null;
	public ?int $user = null;
	public ?int $status = null;
	public int $type = 0;
	

	/**
	 * the rules should be respect by each child model
	 * @return array
	 */
	public function rules(): array
	{
		return [];
	}
}
