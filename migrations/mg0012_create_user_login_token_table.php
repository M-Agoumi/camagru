<?php


use core\Application;
use core\Db\Database;

class mg0012_create_user_login_token_table
{
	public Database $db;

	public function __construct()
	{
		$this->db = Application::$APP->db;
	}

	public function up()
	{
		$this->db->pdo->exec("CREATE TABLE user_token(
									id         int unsigned NOT NULL AUTO_INCREMENT ,
									user       int NOT NULL ,
									token      varchar(128) NOT NULL ,
									used       tinyint NOT NULL default 0,
									created_at timestamp NOT NULL ,
									updated_at timestamp NULL ,
									
									PRIMARY KEY (id),
									KEY fkIdx_131 (user),
									CONSTRAINT FK_130 FOREIGN KEY fkIdx_131 (user) REFERENCES users (id) ON DELETE CASCADE 
								);
					");
	}

	public function down()
	{
		$this->db->pdo->exec("DROP TABLE IF EXISTS user_token");
	}
}
