<?php

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0007_creating_contact_us_table
{
	public function up()
	{
		Migration::create('contact_us', function (Schema $table) {
			$table->id();
			$table->smallInt('logged');
			$table->int('user');
			$table->string('email')->nullable();
			$table->string('title');
			$table->int('ParentId')->nullable();
			$table->text('content');
			$table->smallInt('status')->default(0);
			$table->timestamps();
			$table->foreign('user')->references('entityID')->on('user')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('ParentId')->references('entityID')->on('contact_us')->onUpdate('cascade')->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('contact_us');
	}
}
