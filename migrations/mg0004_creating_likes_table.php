<?php

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0004_creating_likes_table
{
	public function up()
	{
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
	}
}
