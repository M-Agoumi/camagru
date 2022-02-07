<?php

use Simfa\Framework\Application;
use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0006_creating_comment_reacts_table
{
	public function up()
	{
//		$db->pdo->exec("CREATE TABLE `comment_reacts` (
//						 `id`         int NOT NULL AUTO_INCREMENT ,
//						 `comment`    int NOT NULL ,
//						 `user`       int NOT NULL ,
//						 `type`       tinyint NOT NULL ,
//						 `status`     tinyint NOT NULL default 0,
//						 `created_at` timestamp NOT NULL ,
//						 `updated_at` timestamp NULL ,
//
//						PRIMARY KEY (`id`),
//						KEY `fkIdx_64` (`comment`),
//						CONSTRAINT `FK_63` FOREIGN KEY `fkIdx_64` (`comment`) REFERENCES `comments` (`id`),
//						KEY `fkIdx_67` (`user`),
//						CONSTRAINT `FK_66` FOREIGN KEY `fkIdx_67` (`user`) REFERENCES `users` (`id`)
//						);
//					");
		Migration::create('comment_reacts', function (Schema $table) {
			$table->id();
			$table->int('comment');
			$table->int('user');
			$table->smallInt('type')->default(0);
			$table->smallInt('status')->default(0);
			$table->timestamps();
			$table->foreign('user')->references('entityID')->on('user')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('comment')->references('entityID')->on('comment')->onUpdate('cascade')->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('comment_reacts');
//		$db = Application::$APP->db;
//
//		$db->pdo->exec("DROP TABLE IF EXISTS comment_reacts");
	}
}
