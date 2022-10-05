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
	private static array $privateProperties = ['primaryKey', 'tableName', 'nonAttributes', 'protected' , 'errors', 'queryBuilder'];

	/**
	 * child properties that aren't database table attributes
	 */
	protected static array $nonAttributes = [];

	/**
	 * @var array $protected properties from public shows like api
	 */
	protected static array $protected = [];
	/**
	 * @var QueryBuilder
	 */
	private QueryBuilder $queryBuilder;

	/**
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->{$this->primaryKey()};
	}

	/**
	 * @param $id
	 * @return void
	 */
	public function setId($id): void
	{
		$this->{$this->primaryKey()} = $id;
	}

	/**
	 * get table name
	 */
	protected static function tableName(): string
	{
		if (static::$tableName != '')
			return static::$tableName;

		return (lcfirst((new ReflectionClass(static::class))->getShortName()));
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

		if (!$this->getId())
			if (($key = array_search(static::primaryKey(), $attributes)) !== false) {
				unset($attributes[$key]);
			}

		$params = array_map(fn($m) => ":$m", $attributes);
		$attrs = array_map(fn($m) => "`$m`", $attributes);
		$statement = self::prepare(
			"INSERT INTO `$tableName` (". implode(", ", $attrs) . ") values (
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
		$attributes[] = 'updated_at';

		$sql = "UPDATE `$tableName` SET ";
		$first = 1;
		foreach ($attributes as $attr) {
			if (!$first)
				$sql .= ', `' . $attr . '` = :' . $attr;
			else {
				$sql .= '`' . $attr . '` = :' . $attr;
				$first = 0;
			}
		}

		$sql .= ' WHERE ' . $this->primaryKey() . ' = ' . $this->{$this->primaryKey()} . ';';
		$statement = self::prepare($sql);

		foreach ($attributes as $attribute) {
			if ($attribute == 'updated_at')
				$statement->bindValue(":$attribute", date('Y-m-d H:i:s', time()));
			elseif ($attribute == 'created_at' && !$this->{$attribute})
				$statement->bindValue(":$attribute", date('Y-m-d H:i:s', time()));
			else {
				if ($this->{$attribute} instanceof DbModel && $this->{$attribute} == '-1'){
					$statement->bindValue(":$attribute", null);}
				else
					$statement->bindValue(":$attribute", $this->{$attribute});
			}
		}
		$this->updated_at = date('Y-m-d H:i:s', time());

		return $statement->execute();
	}

	/**
	 * @param $key
	 * @param null $value
	 * @param int $object
	 * @return mixed
	 */
    public function getOneBy($key, $value = null, int $object = 1): mixed
	{
        $tableName = $this->tableName();

        if ($value) {
	        $statement = self::prepare("SELECT * FROM $tableName WHERE $key = :value");
	        $statement->bindParam(":value", $value);
        } else {
        	$primary = $this->primaryKey();
        	$statement = self::prepare("SELECT * FROM $tableName WHERE $primary = :key");
			if ($key instanceof $this) {
				$id = $key->getId();
				$statement->bindParam(":key", $id);
			} else
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
						if ($data[$key])
				        	$model->loadData($model->getOneBy($data[$key], '', false));
				        $data[$key] = $model;
			        }
		        }

		        $this->loadData($data);
	        }
            return $this;
        }

        return $statement->fetch(2);
    }
	
	public static function findOne(array $where, array $relations = []): DbModel
	{
		$tableName = static::tableName();
		$attributes = array_keys($where);
		$sql = implode(" AND " ,array_map(fn($attr) => "$attr = :$attr", $attributes));
		$stmt = self::prepare("SELECT * FROM `$tableName` WHERE ". $sql);

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
				if ($data[$key])
					$model->loadData($model->getOneBy($data[$key], '', false));
				$data[$key] = $model;

			}

			$class->loaddata($data);
		}

		return $class;
	}

	/** get all records of a specific entity
	 * @param string $limit
	 * @param string $order
	 * @return array|bool
	 */
	public function findAll(string $limit = '', string $order = 'ASC'): array|bool
	{
		$tableName = static::tableName();
		$limit = !empty($limit) ? 'limit ' . $limit : '';
		$primary = static::primaryKey();
		$stmt = self::prepare("SELECT * FROM $tableName ORDER BY " . $primary . " " . $order . " " . $limit . ';');
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @param array $where where => ['column' => 'value we need', 'column_b' => 'value b']
	 * 		the above example will be where `column` = 'value we need' and `column_b` = 'value b'
	 * @param string $limit
	 * @return array
	 */
	public function findAllBy(array $where, string $limit = ''): array
	{
		$tableName = static::tableName();
		$primary = static::primaryKey();
		$sql = '';
		foreach ($where as $criteria => $value) {
			$sql .= $sql != '' ? ' and ' : '';
			if (is_array($value)) {
				foreach ($value as $key => $item) {
					$sql .= $criteria . ' ' . $key . ' :' . $criteria . ' ';
				}
			} else
				$sql .= $criteria . ' = :' . $criteria;
		}
		$limit = !empty($limit) ? 'limit ' . $limit : '';
		$stmt = self::prepare("SELECT * FROM `$tableName` WHERE ". $sql . " ORDER BY " . $primary ." DESC " . $limit);
		foreach ($where as $key => $item) {
			if (!is_array($item))
				$stmt->bindValue(":$key", $item);
			else {
				foreach ($item as $handler => $value) {
					$stmt->bindValue(":$key", $value);
				}
			}
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
		$stmt = !empty($where) ?    self::prepare("SELECT * FROM `$tableName` WHERE ". $sql) :
									self::prepare("SELECT * FROM `$tableName`");
		foreach ($where as $key => $item) {
			$stmt->bindValue(":$key", $item);
		}
		$stmt->execute();
		$this->totalRecords = $stmt->rowCount();

		return $this->totalRecords;
	}


	public function random($count = 1)
	{
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

	/** delete current object
	 * @param int $confirm
	 * @return bool
	 */
	public function delete(int $confirm = 0): bool
	{
		if ($confirm) {
			$tableName = $this->tableName();
			$entity = $this->getId();
			$stmt = self::prepare('DELETE FROM `' . $tableName . '` WHERE ' . static::primaryKey() . ' = :primaryValue');

			$stmt->bindValue(":primaryValue", $entity);

			if ($stmt->execute()) {
				foreach (static::attributes() as $attribute) {
					unset($this->{$attribute});
				}

				return true;
			}
		}

		return false;
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
		if ($this->getId())
			return (string)$this->getId();

		return '-1';
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return mixed
	 * @property string $name
	 */
	public function __call($name, $arguments)
	{
		if ($name == 'set')
			die('here');
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

	/**
	 * @return QueryBuilder
	 */
	public function queryBuilder(): QueryBuilder
	{
		if (property_exists($this, 'queryBuilder'))
			return $this->queryBuilder;

		$this->queryBuilder = new QueryBuilder(static::tableName(), static::primaryKey());

		return $this->queryBuilder();
	}

	/**
	 * @return array
	 */
	public function relationships(): array
	{
		return [];
	}
}
