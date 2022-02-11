<?php

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0008_create_languages_table
{
	public function up()
	{
		Migration::create('language', function (Schema $table){
			$table->id();
			$table->string('language');
			$table->timestamps();

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('language');
	}
}
