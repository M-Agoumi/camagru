<?php

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0006_creating_comment_reacts_table
{
	public function up()
	{
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
	}
}
