<?php


use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;


class mg0013_create_banned_ip_table
{


	public function up()
	{
		Migration::create('banned_ip', function (Schema $table){
			$table->id();
			$table->string('address');
			$table->smallInt('status');
			$table->text('comment')->nullable();
			$table->timestamps();

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('banned_ip');
	}
}
