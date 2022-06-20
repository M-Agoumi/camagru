<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   Migrate.php                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: magoumi <magoumi@student.1337.ma>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2022/02/6 18:39:13 by magoumi            #+#    #+#             */
/*   Updated: 2022/02/6 18:39:13 by magoumi           ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */


namespace Simfa\Framework\CLI\Commands;

use Simfa\Framework\Application;
use Simfa\Framework\CLI\BaseCommand;
use Simfa\Framework\CLI\CLIApplication;
use PDO;
use PDOException;

class Migrate extends BaseCommand
{

	/**
	 * @var string
	 */
	protected static string $command = 'migrate';


	/** create database using our DSN connection [config/db.conf]
	 * @return string
	 */
	public function create():string
	{
		echo "creating database..." . PHP_EOL;
		if (!$databaseConfig = $this->getDatabaseConfig())
			die("Config File is not found" . PHP_EOL);
		if (!$database = $this->getDatabaseName($databaseConfig['DB_DSN']))
			die("config file is miss written please check config/db.config.example\n");

		/** get connection config from config file */
		$dsn = $this->getDatabaseDSN($databaseConfig['DB_DSN']);
		$user = $databaseConfig['DB_USER'] ?? NULL;
		$pass = $databaseConfig['DB_PASSWORD'] ?? NULL;

		/** try to connect to database and create the database */
		try {
			$dbh = new PDO($dsn, $user , $pass);

			$dbh->exec("CREATE DATABASE IF NOT EXISTS `$database` ;
                    CREATE USER '$user'@'localhost' IDENTIFIED BY '$pass';
                    GRANT ALL ON `$database`.* TO '$user'@'localhost';
                    FLUSH PRIVILEGES;")
			or die(print_r($dbh->errorInfo(), true));
			echo "database $database created or already exists\n";
			unset($dbh);
		}
		catch (PDOException $e) {
			die("DB ERROR: " . $e->getMessage());
		}

		return '';
	}

	/**
	 * Apply migration files
	 */
	public function migrate()
	{
		$app = CLIApplication::$CLI_APP->getApp();
		$app->db->applyMigrations();
	}

	/**
	 * Reverting migration files
	 */
	public function down()
	{
		$app = CLIApplication::$CLI_APP->getApp();
		$app->db->downMigrations(intval(CLIApplication::$CLI_APP->argv[1] ?? 0));
	}

	/**
	 * get clean database
	 * @return void
	 */
	public function purge()
	{
		$this->down();
		$this->migrate();
	}

	/**
	 * get database connection credentials from the config file
	 * @return array|false|null
	 */
	private function getDatabaseConfig() {
		return file_exists(CLIApplication::$ROOT_DIR . "/config/db.conf") ?
			parse_ini_file(CLIApplication::$ROOT_DIR . "/config/db.conf") : NULL;
	}

	/**
	 * extract the database name
	 * @param string $dsn
	 * @return string|null
	 */
	private function getDatabaseName(string $dsn): ?string
	{
		$config = explode(';', $dsn);
		if (sizeof($config) != 3)
			return NULL;
		if (empty($config[2]))
			return NULL;
		$data = $config[2];
		$data = explode('=', $data);
		if (isset($data[1]) && !empty($data[1]))
			return $data[1];
		return NUll;
	}


	/** extract database DSN
	 * @param string $config
	 * @return string|null
	 */
	private function getDatabaseDSN(string $config): ?string
	{
		$config = explode(';', $config);
		if (sizeof($config) == 3)
			array_pop($config);
		else
			return NULL;
		return implode(';', $config);
	}

	public static function helper(): string
	{
		$helperMessage  = RED . self::$command . RESET . PHP_EOL;
		$helperMessage    .= self::printCommand("create") ."create the project ";
		$helperMessage    .= "database if it doesn't not exist --database fetched from config file" . PHP_EOL;
		$helperMessage    .= self::printCommand("migrate") . "to apply migration from the migrations folder" . PHP_EOL;
		$helperMessage    .= self::printCommand("down") . "revert all migrations" . PHP_EOL;
		$helperMessage    .= CYAN . "        n" . RESET . "\t\t\tAccepts number of migrations to revert (migrate:down N)[migrate:down 1]" . PHP_EOL;
		$helperMessage    .= self::printCommand("purge") . "purge all the tables";


		return $helperMessage;
	}
}
