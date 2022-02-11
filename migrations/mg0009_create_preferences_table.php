<?php

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0009_create_preferences_table
{

	public function up()
	{
		Migration::create('preference', function (Schema $table){
			$table->id();
			$table->int('user');
			$table->int('language')->nullable();
			$table->smallInt('mail')->nullable();
			$table->timestamps();
			$table->foreign('user')->references('entityID')->on('user')->onUpdate('cascade')
				->onDelete('cascade');
			$table->foreign('language')->references('entityID')->on('language')
				->onUpdate('cascade')->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('preference');
//		$this->db->pdo->exec("DROP TABLE IF EXISTS preferences");
	}

}
