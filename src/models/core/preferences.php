<?php


namespace models\core;


use core\Db\DbModel;

class preferences extends DBModel
{

	public ?int $id = null;
	public ?int $user = null;
	public ?int $language = null;

	public function tableName(): string
	{
		return 'preferences';
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

	public static function getPeref(int $user)
	{
		return self::findOne(['user' => $user]);
	}

}