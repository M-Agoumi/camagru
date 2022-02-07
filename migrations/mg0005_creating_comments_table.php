<?php


use Simfa\Framework\Application;
use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0005_creating_comments_table
{
	public function up()
	{
		$db = Application::$APP->db;

//		$db->pdo->exec("CREATE TABLE `comments` (
//							 `id`         int NOT NULL AUTO_INCREMENT ,
//							 `post`       int NOT NULL ,
//							 `user`       int NOT NULL ,
//							 `content`    text NOT NULL ,
//							 `status`     tinyint NOT NULL default 0,
//							 `created_at` timestamp NOT NULL ,
//							 `updated_at` timestamp NULL ,
//
//							PRIMARY KEY (`id`),
//							KEY `fkIdx_51` (`post`),
//							CONSTRAINT `FK_50` FOREIGN KEY `fkIdx_51` (`post`) REFERENCES `posts` (`id`),
//							KEY `fkIdx_54` (`user`),
//							CONSTRAINT `FK_53` FOREIGN KEY `fkIdx_54` (`user`) REFERENCES `users` (`id`)
//							);
//						");
		Migration::create('comment', function (Schema $table) {
			$table->id();
			$table->int('post');
			$table->int('user');
			$table->text('content');
			$table->smallInt('status')->default(0);
			$table->timestamps();
			$table->foreign('post')->references('entityID')->on('post')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user')->references('entityID')->on('user')->onUpdate('cascade')->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('comment');
//		$db = Application::$APP->db;
//
//		$db->pdo->exec("DROP TABLE IF EXISTS comments");
	}
}
