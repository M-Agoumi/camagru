<?php


use Simfa\Framework\Application;
use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0003_creating_password_restore_table
{
	public function up()
	{
		$db = Application::$APP->db;

//		$db->pdo->exec("CREATE TABLE IF NOT EXISTS `password_reset` (
//						 `id`         int NOT NULL AUTO_INCREMENT ,
//						 `email`      varchar(255) NOT NULL ,
//						 `token`      varchar(255) NOT NULL ,
//						 `used`       tinyint NOT NULL default 0,
//						 `created_at` timestamp NOT NULL ,
//						 `updated_at` timestamp NULL ,
//						 PRIMARY KEY (`id`)
//						);");
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
//		$db = Application::$APP->db;
//
//		$db->pdo->exec("DROP TABLE IF EXISTS password_reset");
	}
}
