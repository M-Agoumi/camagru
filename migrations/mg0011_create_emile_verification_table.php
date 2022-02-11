<?php

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0011_create_emile_verification_table
{

	public function up()
	{
		Migration::create('email_token', function (Schema $table){
			$table->id();
			$table->string('email');
			$table->string('token');
			$table->smallInt('used')->default(0);
			$table->timestamps();

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('email_token');
	}
}
