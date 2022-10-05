<?php

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0002_creating_posts_table
{
	public function up()
	{
		Migration::create('post', function (Schema $table) {
			$table->id();
			$table->string('title')->nullable();
			$table->text('comment')->nullable();
			$table->string('picture');
			$table->string('slug')->unique();
			$table->smallInt('status')->default(0);
			$table->smallInt('spoiler')->default(0);
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
