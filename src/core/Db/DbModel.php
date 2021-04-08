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

namespace core\Db;

use models\Model;
use \PDOStatement;

abstract class DbModel extends Model
{
    public ?string $created_at = null;
    public ?string $updated_at = null;

	abstract public function tableName(): string;

	abstract public function attributes(): array;
	
	abstract public function primaryKey(): string;

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
     * @param $key
     * @param $value
     * @return false|User
     */
    public function getOneBy($key, $value)
    {
        $tableName = $this->tableName();

        $statement = self::prepare("SELECT * FROM $tableName WHERE $key = :value");
        $statement->bindParam(":value", $value);
        $statement->execute();

        return $statement->fetchObject();
    }
	
	public static function findOne(array $where)
	{
		$tableName = static::tableName();
		$attributes = array_keys($where);
		$sql = implode("AND " ,array_map(fn($attr) => "$attr = :$attr", $attributes));
		$stmt = self::prepare("SELECT * FROM $tableName WHERE ". $sql);
		foreach ($where as $key => $item) {
			$stmt->bindValue(":$key", $item);
		}
		
		$stmt->execute();
		return $stmt->fetchObject(static::class);
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