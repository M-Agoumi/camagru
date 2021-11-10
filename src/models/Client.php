<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   Client.php                                        :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/10/24 22:44:41 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/10/24 22:44:41 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

namespace   models;

/**
 * Class Client
 */

use core\Db\DbModel;

class Client extends DbModel
{
	protected ?int $entityID = null;
	protected ?string $name = NULL;
	protected ?string $username = null;
	protected ?string $email = NULL;

	/**
	 * @var string $tableName in the database
	 */
	protected static string $tableName = "clients";

	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [
		    'name' => [self::RULE_REQUIRED,[self::RULE_MAX, 'max' => 255]],
			'username' => [self::RULE_REQUIRED,[self::RULE_MAX, 'max' => 255]],
			'email' => [self::RULE_REQUIRED,[self::RULE_MAX, 'max' => 255]]
		];
	}
}
