<?php


use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0015_add_client_table extends Migration
{
	public function up()
	{
		Migration::create('clients', function(Schema $table){
			$table->id();
			$table->string('name')->unique();
			$table->string('username')->unique();
			$table->string('email')->nullable()->unique();
			$table->int('user');
			$table->timestamps();
			$table->foreign('user')->references('EntityID')->on('user')->onUpdate('cascade')->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('clients');
	}
}
