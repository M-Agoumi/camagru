<?php

use Simfa\Framework\Application;
use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0004_creating_likes_table
{
	public function up()
	{
		$db = Application::$APP->db;

//		$db->pdo->exec("CREATE TABLE IF NOT EXISTS `likes` (
//					 	`id`         int NOT NULL AUTO_INCREMENT ,
//					 	`post`       int NOT NULL ,
//					 	`user`       int NOT NULL ,
//					 	`type`       tinyint NOT NULL default 0,
//				 		`status`     tinyint NOT NULL default 0,
//					 	`created_at` timestamp NOT NULL ,
//					 	`updated_at` timestamp NULL ,
//					 	PRIMARY KEY (`id`),
//                       	KEY `fkIdx_38` (`post`),
//						CONSTRAINT `FK_37` FOREIGN KEY `fkIdx_38` (`post`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
//						KEY `fkIdx_41` (`user`),
//						CONSTRAINT `FK_40` FOREIGN KEY `fkIdx_41` (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
//						);");
		Migration::create('like', function (Schema $table) {
			$table->id();
			$table->int('post');
			$table->int('user');
			$table->smallInt('type')->default(0);
			$table->smallInt('status')->default(0);
			$table->timestamps();
			$table->foreign('post')->references('entityID')->on('post')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user')->references('entityID')->on('user')->onUpdate('cascade')->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('like');
//		$db = Application::$APP->db;
//
//		$db->pdo->exec("DROP TABLE IF EXISTS likes");
	}
}
