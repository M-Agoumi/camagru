<?php

use Simfa\Framework\Application;

class mg0004_creating_likes_table
{
	public function up()
	{
		$db = Application::$APP->db;

		$db->pdo->exec("CREATE TABLE IF NOT EXISTS `likes` (
					 	`id`         int NOT NULL AUTO_INCREMENT ,
					 	`post`       int NOT NULL ,
					 	`user`       int NOT NULL ,
					 	`type`       tinyint NOT NULL default 0,
				 		`status`     tinyint NOT NULL default 0,
					 	`created_at` timestamp NOT NULL ,
					 	`updated_at` timestamp NULL ,
					 	PRIMARY KEY (`id`),
                       	KEY `fkIdx_38` (`post`),
						CONSTRAINT `FK_37` FOREIGN KEY `fkIdx_38` (`post`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
						KEY `fkIdx_41` (`user`),
						CONSTRAINT `FK_40` FOREIGN KEY `fkIdx_41` (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
						);");
	}

	public function down()
	{
		$db = Application::$APP->db;

		$db->pdo->exec("DROP TABLE IF EXISTS likes");
	}
}
