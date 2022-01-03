<?php


use Simfa\Framework\Application;
use core\Db\Database;

class mg0013_create_banned_ip_table
{
	public Database $db;

	public function __construct()
	{
		$this->db = Application::$APP->db;
	}

	public function up()
	{
		$this->db->pdo->exec("CREATE TABLE banned_ip(
									id         int unsigned NOT NULL AUTO_INCREMENT ,
									address    varchar(45) NOT NULL ,
									status     tinyint NOT NULL ,
									comment    text NULL ,
									created_at timestamp NOT NULL ,
									updated_at timestamp NULL ,
									
									PRIMARY KEY (id)
								);
					");
	}

	public function down()
	{
		$this->db->pdo->exec("DROP TABLE IF EXISTS banned_ip");
	}
}
