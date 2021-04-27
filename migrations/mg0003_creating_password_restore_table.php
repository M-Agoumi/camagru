<?php


use core\Application;

class mg0003_creating_password_restore_table
{
	public function up()
	{
		$db = Application::$APP->db;

		$db->pdo->exec("CREATE TABLE IF NOT EXISTS `password_reset` (
						 `id`         int NOT NULL AUTO_INCREMENT ,
						 `email`      varchar(255) NOT NULL ,
						 `token`      varchar(255) NOT NULL ,
						 `used`       tinyint NOT NULL default 0,
						 `created_at` timestamp NOT NULL ,
						 `updated_at` timestamp NULL ,
						 PRIMARY KEY (`id`)
						);");
	}

	public function down()
	{
		$db = Application::$APP->db;

		$db->pdo->exec("DROP TABLE IF EXISTS password_reset");
	}
}