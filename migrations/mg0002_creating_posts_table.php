<?php


use Simfa\Framework\Application;
use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0002_creating_posts_table
{
	public function up()
	{
		$db = Application::$APP->db;

//		$db->pdo->exec("CREATE TABLE IF NOT EXISTS `posts` (
//						 `id`         int NOT NULL AUTO_INCREMENT ,
//						 `title`      varchar(255) NULL ,
//						 `comment`    text NULL ,
//						 `picture`    varchar(255) NOT NULL ,
//						 `slug`       varchar(255) NOT NULL UNIQUE,
//						 `created_at` timestamp NOT NULL ,
//						 `updated_at` timestamp NULL ,
//						 `status`     tinyint NOT NULL default 0,
//						 `author`     int NULL ,
//
//						PRIMARY KEY (`id`),
//						KEY `fkIdx_23` (`author`),
//						CONSTRAINT `FK_22` FOREIGN KEY `fkIdx_23` (`author`) REFERENCES `users` (`id`)
//						);
//
//						");
		Migration::create('post', function (Schema $table) {
			$table->id();
			$table->string('title')->nullable();
			$table->text('comment')->nullable();
			$table->string('picture');
			$table->string('slug')->unique();
			$table->smallInt('status')->default(0);
			$table->int('author');
			$table->timestamps();
			$table->foreign('author')->references('entityID')->on('user')->onUpdate('cascade')->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('post');
//		$db = Application::$APP->db;
//
//		$db->pdo->exec("DROP TABLE IF EXISTS posts");
	}
}
