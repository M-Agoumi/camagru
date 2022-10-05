<?php


namespace Simfa\Model;


use Simfa\Framework\Db\DbModel;

class Preference extends DBModel
{

	public ?int $entityID = null;
	public ?int $user = null;
	public ?int $language = null;
	public ?int $mail = 1;

	/**
	 * the rules should be respect by each child model
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'user' => [self::RULE_REQUIRED],
			'language' => [self::RULE_REQUIRED]
		];
	}

	public static function getPerf(int $user)
	{
		$results = self::findOne(['user' => $user]);

		if ($results)
			return $results;
		return NULL;
	}

}
