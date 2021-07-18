<?php


use core\Application;

class mg0009_create_preferences_table
{
	public \core\Db\Database $db;

	public function __construct()
	{
		$this->db = Application::$APP->db;
	}

	public function up()
	{
		$this->db->pdo->exec("CREATE TABLE IF NOT EXISTS `preferences` (
								`id`       int NOT NULL AUTO_INCREMENT ,
								`user`     int NOT NULL ,
								`language` int NOT NULL ,
								`created_at` timestamp NOT NULL ,
					            `updated_at` timestamp NULL ,
								
								PRIMARY KEY (`id`),
								KEY `fkIdx_102` (`language`),
								CONSTRAINT `FK_101` FOREIGN KEY `fkIdx_102` (`language`) REFERENCES `languages` (`id`),
								KEY `fkIdx_93` (`user`),
								CONSTRAINT `FK_92` FOREIGN KEY `fkIdx_93` (`user`) REFERENCES `users` (`id`)
								);
					");
	}

	public function down()
	{
		$this->db->pdo->exec("DROP TABLE IF EXISTS preferences");
	}

}
