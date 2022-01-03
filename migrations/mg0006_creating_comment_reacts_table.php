<?php

use Simfa\Framework\Application;

class mg0006_creating_comment_reacts_table
{
	public function up()
	{
		$db = Application::$APP->db;

		$db->pdo->exec("CREATE TABLE `comment_reacts` (
						 `id`         int NOT NULL AUTO_INCREMENT ,
						 `comment`    int NOT NULL ,
						 `user`       int NOT NULL ,
						 `type`       tinyint NOT NULL ,
						 `status`     tinyint NOT NULL default 0,
						 `created_at` timestamp NOT NULL ,
						 `updated_at` timestamp NULL ,
						
						PRIMARY KEY (`id`),
						KEY `fkIdx_64` (`comment`),
						CONSTRAINT `FK_63` FOREIGN KEY `fkIdx_64` (`comment`) REFERENCES `comments` (`id`),
						KEY `fkIdx_67` (`user`),
						CONSTRAINT `FK_66` FOREIGN KEY `fkIdx_67` (`user`) REFERENCES `users` (`id`)
						);
					");
	}

	public function down()
	{
		$db = Application::$APP->db;

		$db->pdo->exec("DROP TABLE IF EXISTS comment_reacts");
	}
}
