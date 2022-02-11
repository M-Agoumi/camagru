<?php


use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0010_create_roles_table
{
	public function up()
	{
		Migration::create('role', function (Schema $table){
			$table->id();
			$table->int('user');
			$table->smallInt('super_admin')->default(0);
			$table->smallInt('users')->default(0);
			$table->smallInt('posts')->default(0);
			$table->smallInt('comments')->default(0);
			$table->smallInt('likes')->default(0);
			$table->smallInt('promote')->default(0);
			$table->int('created_by');
			$table->int('updated_by');
			$table->smallInt('mail');
			$table->timestamps();
			$table->foreign('user')->references('entityID')->on('user')->onUpdate('cascade')
				->onDelete('cascade');
			$table->foreign('created_by')->references('entityID')->on('user')->onUpdate('cascade')
				->onDelete('cascade');
			$table->foreign('updated_by')->references('entityID')->on('user')->onUpdate('cascade')
				->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('role');
	}
}
