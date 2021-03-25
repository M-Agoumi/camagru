<?php
# **************************************************************************** #
#                                                                              #
#                                                         :::      ::::::::    #
#    DbModel.php                                        :+:      :+:    :+:    #
#                                                     +:+ +:+         +:+      #
#    By: magoumi <agoumi.mohamed@outlook.com>       +#+  +:+       +#+         #
#                                                 +#+#+#+#+#+   +#+            #
#    Created: 2021/03/24 20:41:15 by magoumi           #+#    #+#              #
#    Updated: 2021/03/24 20:41:15 by magoumi          ###   ########lyon.fr    #
#                                                                              #
# **************************************************************************** #

include_once Application::$ROOT_DIR . "/models/Model.php";

abstract class DbModel extends Model
{
	abstract public function tableName(): string;

	abstract public function attributes(): array;

    /**
     * save method to insert data of a specific model to it's table
     * @return bool
     */
    public function save(): bool
    {
		$tableName = $this->tableName();
		$attributes = $this->attributes();
		$params = array_map(fn($m) => ":$m", $attributes);
		$statement = self::prepare(
			"INSERT INTO $tableName (". implode(", ", $attributes) . ") value (
				" . implode(", ", $params) . ")"
		);

		foreach ($attributes as $attribute) {
			$statement->bindValue(":$attribute", $this->{$attribute});
		}

		return $statement->execute();
	}

    /**
     * this method is just to keep the code clean
     * instead of writing the whole prepare statement on the app instance
     * call this method :)
     * @param string $sql
     * @return false|PDOStatement
     */
	public static function prepare(string $sql)
	{
		return Application::$APP->db->pdo->prepare($sql);
	}
}