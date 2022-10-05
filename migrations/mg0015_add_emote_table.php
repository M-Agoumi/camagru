<?php


use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0015_add_emote_table
{
	public function up()
	{
		Migration::create('emote', function(Schema $table){
			$table->id();
			$table->string('name')->unique();
			$table->string('file')->unique();
			$table->smallInt('type')->default('0');
			$table->timestamps();

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('emote');
	}
}
