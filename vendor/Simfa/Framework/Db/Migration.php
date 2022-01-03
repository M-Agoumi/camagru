<?php

namespace Simfa\Framework\Db;

use Simfa\Framework\Application;
use Simfa\Framework\Db\Migration\Schema;

abstract class Migration
{
	private static string $table = '';
	private static ?Database $db = null;

	public static function  create(string $tableName, \Closure $closure)
	{
		self::$table = $tableName;
		$schema = $closure(new Schema());
		if (!$schema)
			die('schema is null perhaps you forgot to return your table schema' . PHP_EOL);

		$sql = self::objectToSQL($schema);

		self::$db = Application::$APP->db;
		self::$db->pdo->exec($sql);
	}

	public static function drop(string $tableName)
	{
		/** get database connection */
		self::$db = Application::$APP->db;
		self::$db->pdo->exec("DROP TABLE " . $tableName);
	}

	private static function objectToSQL($schema): string
	{
		$sql = 'CREATE TABLE `' . self::$table . '` (' . PHP_EOL;
		$first = true;

		foreach ($schema->getColumns() as $column)
		{
			$sql .= !$first ? ',' . PHP_EOL : '';
			$first = false;

			$sql .= "\t" . $column->toString();
		}

		$sql .= "\n) ENGINE=InnoDB;";

		return $sql . PHP_EOL;
	}

	abstract function up();

	abstract function down();
}
