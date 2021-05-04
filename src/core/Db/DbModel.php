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

use core\Application;
use models\Model;
use PDO;
use PDOStatement;

abstract class DbModel extends Model
{
    public ?string $created_at = null;
    public ?string $updated_at = null;

	abstract public function tableName(): string;

	abstract public function attributes(): array;
	
	abstract public function primaryKey(): string;

	abstract public function getId(): ?int;

    /**
     * save method to insert data of a specific model to it's table
     * @return bool
     */
    public function save(): bool
    {
		$tableName = $this->tableName();
		$attributes = $this->attributes();
		array_push($attributes, 'created_at');
		$params = array_map(fn($m) => ":$m", $attributes);
		$statement = self::prepare(
			"INSERT INTO $tableName (". implode(", ", $attributes) . ") value (
				" . implode(", ", $params) . ")"
		);

		foreach ($attributes as $attribute) {
			if ($attribute == 'created_at' && !$this->{$attribute})
				$statement->bindValue(":$attribute", date('Y-m-d H:i:s', time()));
			else
				$statement->bindValue(":$attribute", $this->{$attribute});
		}

		return $statement->execute();
	}

	/**
	 * update method to update data of a specific record in a specific model
	 * @return bool
	 */
	public function update(): bool
	{
		$tableName = $this->tableName();
		$attributes = $this->attributes();
		array_push($attributes, 'updated_at');

		$sql = "UPDATE $tableName SET ";
		$first = 1;
		foreach ($attributes as $attr) {
			if (!$first)
				$sql .= ", " . $attr . " = :" . $attr;
			else {
				$sql .= $attr . " = :" . $attr;
				$first = 0;
			}
		}

		$sql .= " WHERE " . $this->primaryKey() . "=" . $this->{$this->primaryKey()} . ";";

		$statement = self::prepare($sql);

		foreach ($attributes as $attribute) {
			if ($attribute == 'updated_at')
				$statement->bindValue(":$attribute", date('Y-m-d H:i:s', time()));
			else
				$statement->bindValue(":$attribute", $this->{$attribute});
		}

		return $statement->execute();
	}

    /**
     * @param $key
     * @param $value
     * @return false|Model
     */
    public function getOneBy($key, $value = null)
    {
        $tableName = $this->tableName();

        if ($value) {
	        $statement = self::prepare("SELECT * FROM $tableName WHERE $key = :value");
	        $statement->bindParam(":value", $value);
        } else {
        	$primary = $this->primaryKey();
        	$statement = self::prepare("SELECT * FROM $tableName WHERE $primary = :key");
	        $statement->bindParam(":key", $key);
        }
	    $statement->execute();

        return $statement->fetchObject();
    }
	
	public static function findOne(array $where)
	{
		$tableName = static::tableName();
		$attributes = array_keys($where);
		$sql = implode(" AND " ,array_map(fn($attr) => "$attr = :$attr", $attributes));
		$stmt = self::prepare("SELECT * FROM $tableName WHERE ". $sql);
		foreach ($where as $key => $item) {
			$stmt->bindValue(":$key", $item);
		}
		$stmt->execute();

		return $stmt->fetchObject(static::class);
	}

	/** get all records of a specific entity
	 * @return mixed
	 */
	public function findAll(){
		$tableName = static::tableName();
		$stmt = self::prepare("SELECT * FROM $tableName;");
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @param array $where
	 * @return array
	 */
	public function findAllBy(array $where)
	{
		$tableName = static::tableName();
		$attributes = array_keys($where);
		$sql = implode(" AND " ,array_map(fn($attr) => "$attr = :$attr", $attributes));
		$stmt = self::prepare("SELECT * FROM $tableName WHERE ". $sql);
		foreach ($where as $key => $item) {
			$stmt->bindValue(":$key", $item);
		}
		$stmt->execute();

		return $stmt->fetchAll(2);
	}

	/** return the count number of an element
	 * @param array $where
	 * @return int
	 */
	public function getCount(array $where):int
	{
		$tableName = static::tableName();
		$attributes = array_keys($where);
		$sql = implode(" AND " ,array_map(fn($attr) => "$attr = :$attr", $attributes));
		$stmt = self::prepare("SELECT * FROM $tableName WHERE ". $sql);
		foreach ($where as $key => $item) {
			$stmt->bindValue(":$key", $item);
		}
		$stmt->execute();

		return $stmt->rowCount();
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