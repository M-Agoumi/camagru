<?php

namespace models\core;


use core\Db\DbModel;

class Languages extends DBModel
{

	public ?int $id = null;
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

	public function tableName(): string
	{
		return 'languages';
	}

	public function attributes(): array
	{
		return ['user', 'language'];
	}

	public function primaryKey(): string
	{
		return 'id';
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public static function getLang(int $id)
	{
		return self::findOne(['id' => $id]);
	}
}
