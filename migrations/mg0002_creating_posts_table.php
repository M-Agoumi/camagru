<?php


use core\Application;

class mg0002_creating_posts_table
{
	public function up()
	{
		$db = Application::$APP->db;

		$db->pdo->exec("CREATE TABLE IF NOT EXISTS `posts` (
						 `id`         int NOT NULL AUTO_INCREMENT ,
						 `title`      varchar(255) NULL ,
						 `comment`    text NULL ,
						 `picture`    varchar(255) NOT NULL ,
						 `slug`       varchar(255) NOT NULL UNIQUE,
						 `created_at` timestamp NOT NULL ,
						 `updated_at` timestamp NULL ,
						 `status`     tinyint NOT NULL default 0,
						 `author`     int NULL ,
						
						PRIMARY KEY (`id`),
						KEY `fkIdx_23` (`author`),
						CONSTRAINT `FK_22` FOREIGN KEY `fkIdx_23` (`author`) REFERENCES `users` (`id`)
						);");
	}

	public function down()
	{
		$db = Application::$APP->db;

		$db->pdo->exec("DROP TABLE IF EXISTS posts");
	}
}