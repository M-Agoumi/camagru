<?php

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0019_create_edits_template_table
{
	private string $tableName = 'template';

	public function up()
	{
		Migration::create($this->tableName, function (Schema $table){
			$table->id();
			$table->int('user');
			$table->text('content');
			$table->timestamps();

			$table->foreign('user')->references('entityID')->on('user')->onUpdate('cascade')
				->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop($this->tableName);
	}
}
