<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   Config.php                                        :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2022/06/19 13:50:30 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2022/06/19 13:50:30 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

namespace   Model;

/**
 * Class Config
 */

use Simfa\Framework\Db\DbModel;

/**
 * @method setName(string $string)
 * @method getName()
 * @method setValue(string $string)
 * @method getValue()
 */
class Config extends DbModel
{
	protected ?int $entityID = null;
	protected ?string $name = NULL;
	protected ?string $value = NULL;
	

	protected static string $tableName = "config";

	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [
		    'name' => [self::RULE_REQUIRED,[self::RULE_MAX, 'max' => 255]],
			'value' => [[self::RULE_MAX, 'max' => 255]]
		];
	}
}
