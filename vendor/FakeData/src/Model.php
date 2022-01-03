<?php

namespace vendor\FakeData\src;

use Simfa\Db\DbModel;

class Model
{
	/**
	 * @var DbModel|string $model
	 * @throws \Exception
	 */
	public function model($model)
	{
		$model = $this->getDbModelOrFail($model);

		return ($model->random(1)[0][$model::primaryKey()]);
	}

	/**
	 * @param $model
	 * @return DbModel
	 * @throws \Exception
	 */
	private function getDbModelOrFail($model):DbModel
	{
		if (is_string($model))
			$model = new $model();

		if ($model instanceof DbModel)
			return $model;

		throw new \Exception('argument expect to be DbModel, got ' . get_class($model) . ' instead');
	}
}
