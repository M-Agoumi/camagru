<?php

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0017_create_application_config_table
{
	public function up()
	{
		Migration::create('config', function (Schema $table){
			$table->id();
			$table->string('name');
			$table->string('value');
			$table->timestamps();

			return $table;
		});

		$config = new \Model\Config();
		$config->setName('user/profile/cover');
		$config->setValue('default_cover.png');

		$config->save();

	}

	public function down()
	{
		Migration::drop('config');
	}
}
