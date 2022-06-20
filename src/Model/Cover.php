<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   Cover.php                                         :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2022/06/19 14:54:33 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2022/06/19 14:54:33 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

namespace   Model;

/**
 * Class Cover
 */

use Simfa\Framework\Db\DbModel;

/**
 * @method setImage(string $value)
 * @method setName(string $key)
 * @method getImage()
 */
class Cover extends DbModel
{
	protected ?int $entityID = null;
	protected ?string $name = null;
	protected ?string $image = NULL;
	

	protected static string $tableName = "cover";

	protected static array $protected = ['entityID'];
	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [
		    'image' => [self::RULE_REQUIRED,[self::RULE_MAX, 'max' => 255]]
		];
	}
}
