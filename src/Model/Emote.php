<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   Emote.php                                         :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2022/02/16 17:11:51 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2022/02/16 17:11:51 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

namespace   Model;

/**
 * Class Emote
 */

use Simfa\Framework\Db\DbModel;

/**
 * @method setName(string $name):void
 * @method getName():string
 * @method setFile(string $fileName):void
 * @method getFile():string
 */
class Emote extends DbModel
{
	protected ?int $entityID = null;
	protected ?string $name = NULL;
	protected ?string $file = NULL;

	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [
		    'name' => [self::RULE_REQUIRED,[self::RULE_MAX, 'max' => 255]],
			'file' => [self::RULE_REQUIRED,[self::RULE_MAX, 'max' => 255]]
		];
	}
}
