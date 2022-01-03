<?php


namespace Simfa\Model;


use Simfa\Framework\Db\DbModel;

class Preferences extends DBModel
{

	public ?int $id = null;
	public ?int $user = null;
	public ?int $language = null;
	public ?int $commentsMail = null;

	protected static string $primaryKey = 'id';

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
