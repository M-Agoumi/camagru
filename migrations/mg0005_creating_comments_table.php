<?php


use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0005_creating_comments_table
{
	public function up()
	{
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
	}
}
