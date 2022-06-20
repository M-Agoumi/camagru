<?php

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0016_create_user_background_table
{
	public function up()
	{
		Migration::create('user_background', function (Schema $table){
			$table->id();
			$table->int('user');
			$table->smallInt('type')->default(0);
			$table->string('image');
			$table->timestamps();
			$table->foreign('user')->references('entityID')->on('user')->onUpdate('cascade')
				->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('user_background');
	}
}
