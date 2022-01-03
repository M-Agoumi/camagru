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

namespace Simfa\Framework\Db;

use Simfa\Framework\Application;
use Simfa\Model\Model;
use PDO;
use PDOStatement;
use ReflectionClass;
use ReflectionProperty;

abstract class DbModel extends Model
{
    public ?string $created_at = null;
    public ?string $updated_at = null;

	/**
	 * @var string $tableName on the database
	 */
	protected static string $tableName = '';

	/**
	 * @var string $primaryKey of the table
	 */
	protected static string $primaryKey = 'entityID';

	/**
	 * framework properties that aren't database table attributes
	 */
	private static array $privateProperties = ['primaryKey', 'tableName', 'nonAttributes', 'protected' , 'errors'];

	/**
	 * child properties that aren't database table attributes
	 */
	protected static array $nonAttributes = [];

	/**
	 * @var array $protected properties from public shows like api
	 */
	protected static array $protected = [];

	public function getId(): ?int
	{
		return $this->{$this->primaryKey()};
	}

	/**
	 * get table name
	 */
	protected function tableName(): string
	{
		$static = !(isset($this) && get_class($this) == __CLASS__);

		if ($static) {
			if (static::$tableName != '')
				return lcfirst(static::$tableName);

			return (lcfirst((new ReflectionClass(static::class))->getShortName()));
		}

		if ($this->tableName != '')
			return $this->tableName;

		return lcfirst((new ReflectionClass($this))->getShortName());
	}

	/**
	 * get table primary key
	 */
	public static function primaryKey(): string
	{
		return static::$primaryKey;
	}

	/**
	 * get class attributes (properties)
	 */
	protected static function attributes(): array
	{
		$attributes = [];
		$reflection = new ReflectionClass(static::class);
		/** @var $atts ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED */
		$atts = $reflection->getProperties();
		foreach ($atts as $att)
		{
			if (!in_array($att->name, static::$privateProperties) && !in_array($att->name, static::$nonAttributes))
				$attributes[] = $att->name;
		}

		return $attributes;
	}

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
			if ($attribute == 'created_at' && !$this->{$attribute})
				$statement->bindValue(":$attribute", date('Y-m-d H:i:s', time()));
			else
				$statement->bindValue(":$attribute", $this->{$attribute});
		}

		$done = $statement->execute();

		/** let's get our saved record id */
	    $statement = self::prepare("SELECT LAST_INSERT_ID()");
	    $statement->execute();
	    $record = $statement->fetch(0);
	    if ($record) {
			$this->{$this->primaryKey()} = $record[0];
			$this->created_at = date('Y-m-d H:i:s', time());
	    }

		return $done;
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

		$this->updated_at = date('Y-m-d H:i:s', time());

		return $statement->execute();
	}

    /**
     * @param $key
     * @param $value
     * @return false|Model
     */
    public function getOneBy($key, $value = null, $object = 1)
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

        if ($object){
        	$data = $statement->fetch(2);
        	if ($data) {
		        if (method_exists($this, 'relationships')) {
			        $relations = $this->relationships();
			        foreach ($relations as $key => $value) {
				        $model = new $value;
				        $model->loadData($model->getOneBy($data[$key], '', false));
				        $data[$key] = $model;
			        }
		        }

		        $this->loadData($data);
	        }
            return $statement->fetchObject();
        }

        return $statement->fetch(2);
    }
	
	public static function findOne(array $where, array $relations = []): DbModel
	{
		$tableName = static::tableName();
		$attributes = array_keys($where);
		$sql = implode(" AND " ,array_map(fn($attr) => "$attr = :$attr", $attributes));
		$stmt = self::prepare("SELECT * FROM $tableName WHERE ". $sql);
		foreach ($where as $key => $item) {
			$stmt->bindValue(":$key", $item);
		}
		$stmt->execute();

		$data = $stmt->fetch(2);

		$class = static::class;
		$class = new $class();

		if ($data) {
			foreach ($relations as $key => $value) {
				$model = new $value;
				$model->loadData($model->getOneBy($data[$key], '', false));
				$data[$key] = $model;

			}

			$class->loaddata($data);
		}

		return $class;
	}

	/** get all records of a specific entity
	 * @return mixed
	 */
	public function findAll(string $limit = '', string $order = 'ASC'){
		$tableName = static::tableName();
		$limit = !empty($limit) ? 'limit ' . $limit : '';
		$primary = static::primaryKey();
		$stmt = self::prepare("SELECT * FROM $tableName ORDER BY " . $primary . " " . $order . " " . $limit . ';');
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @param array $where
	 * @return array
	 */
	public function findAllBy(array $where, string $limit = '')
	{
		$tableName = static::tableName();
		$attributes = array_keys($where);
		$primary = static::primaryKey();
		$sql = implode(" AND " ,array_map(fn($attr) => "$attr = :$attr", $attributes));
		$limit = !empty($limit) ? 'limit ' . $limit : '';
		$stmt = self::prepare("SELECT * FROM $tableName WHERE ". $sql . " ORDER BY " . $primary ." DESC " . $limit);
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
	public function getCount(array $where = []):int
	{
		$tableName = static::tableName();
		$attributes = array_keys($where);
		$sql = implode(" AND " ,array_map(fn($attr) => "$attr = :$attr", $attributes));
		$stmt = !empty($where) ?    self::prepare("SELECT * FROM $tableName WHERE ". $sql) :
									self::prepare("SELECT * FROM $tableName");
		foreach ($where as $key => $item) {
			$stmt->bindValue(":$key", $item);
		}
		$stmt->execute();
		$this->totalRecords = $stmt->rowCount();

		return $this->totalRecords;
	}


	public function random($count = 1)
	{
		/** SELECT * FROM tbl AS t1 JOIN (SELECT id FROM tbl ORDER BY RAND() LIMIT 10) as t2 ON t1.id=t2.id */
		/** SELECT * FROM users AS t1 JOIN (SELECT id FROM users ORDER BY RAND() LIMIT 10) as t2 ON t1.id=t2.id */
		$tableName = static::tableName();
		$primary = static::primaryKey();

		$stmt = self::prepare(
			'SELECT * FROM ' . $tableName .' AS t1 JOIN (SELECT ' .
			$primary . ' FROM ' . $tableName . ' ORDER BY RAND() LIMIT ' .
			$count . ') as t2 ON t1.' . $primary . '=t2.' . $primary
		);

		$stmt->execute();

		return $stmt->fetchAll(2);
	}

    /**
     * this method is just to keep the code clean
     * instead of writing the whole prepare statement on the app instance
     * call this method :)
     * @param string $sql
     * @return false|PDOStatement
     */
	protected static function prepare(string $sql)
	{
		return Application::$APP->db->pdo->prepare($sql);
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string)$this->getId();
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return mixed
	 * @property string $name
	 */
	public function __call($name, $arguments)
	{
		if (substr($name, 0, 3 ) === "get")
		{
			$name = lcfirst(ltrim($name, 'get'));

			if (property_exists($this, $name))
				return $this->$name;

			die('property ' . $name . ' does not exist in ' . get_class($this));
		}

		if (substr($name, 0, 3 ) === "set")
		{
			$name = lcfirst(ltrim($name, 'set'));
			$this->$name = $arguments[0];
			return ;
		}

		throw new \BadMethodCallException('method ' . $name . ' does not exist in class ' . get_class($this));
	}
}
