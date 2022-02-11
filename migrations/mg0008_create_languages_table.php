<?php

use Simfa\Framework\Application;
use Simfa\Framework\Db\Database;
use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0008_create_languages_table
{
//	public Simfa\Framework\Db\Database $db;
//
//	public function __construct()
//	{
//		$this->db = Application::$APP->db;
//	}

	public function up()
	{
//		$this->db->pdo->exec("CREATE TABLE IF NOT EXISTS `languages` (
//								`id`       int NOT NULL AUTO_INCREMENT ,
//								`language` varchar(45) NOT NULL ,
//								`created_at` timestamp NOT NULL ,
//					            `updated_at` timestamp NULL ,
//
//								PRIMARY KEY (`id`)
//								);
//					");
		Migration::create('language', function (Schema $table){
			$table->id();
			$table->string('language');
			$table->timestamps();

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('language');
//		$this->db->pdo->exec("DROP TABLE IF EXISTS languages");
	}
}
