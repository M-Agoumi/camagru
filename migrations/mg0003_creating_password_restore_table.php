<?php


use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0003_creating_password_restore_table
{
	public function up()
	{
		Migration::create('password_reset', function (Schema $table) {
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
		Migration::drop('password_reset');
	}
}
