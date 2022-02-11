<?php

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0012_create_user_login_token_table
{
	public function up()
	{
		Migration::create('user_token', function (Schema $table){
			$table->id();
			$table->int('user');
			$table->string('token');
			$table->smallInt('used')->default(0);
			$table->timestamps();
			$table->foreign('user')->references('entityID')->on('user')->onUpdate('cascade')
				->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('user_token');
	}
}
