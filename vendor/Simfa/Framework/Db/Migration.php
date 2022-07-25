<?php

namespace Simfa\Framework\Db;

use Closure;
use Simfa\Framework\Application;
use Simfa\Framework\Db\Migration\Schema;

abstract class Migration
{
	/**
	 * @var string
	 */
	private static string $table = '';

	/**
	 * @var Database|null
	 */
	private static ?Database $db = null;

	/**
	 * @param string $tableName
	 * @param Closure $closure
	 * @return void
	 */
	public static function create(string $tableName, Closure $closure): void
	{
		self::$table = $tableName;
		$schema = $closure(new Schema());
		if (!$schema)
			die('schema is null perhaps you forgot to return your table schema' . PHP_EOL);

		$sql = self::objectToSQL($schema);

		self::$db = Application::$APP->db;
		self::$db->pdo->exec($sql);
	}

	/**
	 * @param string $tableName
	 * @return void
	 */
	public static function drop(string $tableName): void
	{
		/** get database connection */
		self::$db = Application::$APP->db;
		self::$db->pdo->exec('DROP TABLE IF EXISTS `' . $tableName . '`');
	}

	/**
	 * @param $schema
	 * @return string
	 */
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

	/**
	 * @return void
	 */
	abstract function up(): void;

	/**
	 * @return void
	 */
	abstract function down(): void;
}
