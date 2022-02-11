<?php

namespace Simfa\Model;

use Simfa\Framework\Db\DbModel;

/**
 * @method setLanguage(string $lang)
 */
class Language extends DBModel
{

	public ?int $entityID = null;
	public ?string $language = null;

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
		return self::findOne(['entityID' => $id]);
	}
}
