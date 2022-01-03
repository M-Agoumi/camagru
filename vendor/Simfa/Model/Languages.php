<?php

namespace Simfa\Model;

use Simfa\Framework\Db\DbModel;

class Languages extends DBModel
{

	public ?int $id = null;
	public ?string $language = null;

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
		return [
			'language' => [self::RULE_REQUIRED]
		];
	}


	/**
	 * @param int $id
	 * @return DbModel language
	 */
	public static function getLang(int $id): DbModel
	{
		return self::findOne(['id' => $id]);
	}
}
