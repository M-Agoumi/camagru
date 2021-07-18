<?php

use core\Application;

class mg0008_create_languages_table
{
	public \core\Db\Database $db;

	public function __construct()
	{
		$this->db = Application::$APP->db;
	}

	public function up()
	{
		$this->db->pdo->exec("CREATE TABLE IF NOT EXISTS `languages` (
								`id`       int NOT NULL AUTO_INCREMENT ,
								`language` varchar(45) NOT NULL ,
								`created_at` timestamp NOT NULL ,
					            `updated_at` timestamp NULL ,

								PRIMARY KEY (`id`)
								);
					");
	}

	public function down()
	{
		$this->db->pdo->exec("DROP TABLE IF EXISTS languages");
	}
}
