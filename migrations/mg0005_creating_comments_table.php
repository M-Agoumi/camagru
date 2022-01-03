<?php


use Simfa\Framework\Application;

class mg0005_creating_comments_table
{
	public function up()
	{
		$db = Application::$APP->db;

		$db->pdo->exec("CREATE TABLE `comments` (
							 `id`         int NOT NULL AUTO_INCREMENT ,
							 `post`       int NOT NULL ,
							 `user`       int NOT NULL ,
							 `content`    text NOT NULL ,
							 `status`     tinyint NOT NULL default 0,
							 `created_at` timestamp NOT NULL ,
							 `updated_at` timestamp NULL ,
							
							PRIMARY KEY (`id`),
							KEY `fkIdx_51` (`post`),
							CONSTRAINT `FK_50` FOREIGN KEY `fkIdx_51` (`post`) REFERENCES `posts` (`id`),
							KEY `fkIdx_54` (`user`),
							CONSTRAINT `FK_53` FOREIGN KEY `fkIdx_54` (`user`) REFERENCES `users` (`id`)
							);
						");
	}

	public function down()
	{
		$db = Application::$APP->db;

		$db->pdo->exec("DROP TABLE IF EXISTS comments");
	}
}
