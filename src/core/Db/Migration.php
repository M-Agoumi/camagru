<?php

namespace core\Db;

use core\Application;
use core\Db\Migration\Schema;

class Migration
{
	private static string $table = '';
	private static ?Database $db = null;

	public static function  create(string $tableName, \Closure $closure)
	{
		self::$table = $tableName;
		$schema = $closure(new Schema());

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
}
