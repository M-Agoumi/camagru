<?php

use Simfa\Framework\Application;
use Simfa\Framework\Db\Database;

class mg0011_create_emile_verification_table
{
	public Database $db;

	public function __construct()
	{
		$this->db = Application::$APP->db;
	}

	public function up()
	{
		$this->db->pdo->exec("CREATE TABLE emailToken(
									 id			int unsigned NOT NULL AUTO_INCREMENT ,
									 email 		VARCHAR(255) NOT NULL UNIQUE,
									 token		VARCHAR(32) NOT NULL COMMENT 'Email Verification Code',
									 used		tinyint default 0,	
									 created_at	timestamp NOT NULL default CURRENT_TIMESTAMP,
									 updated_at	timestamp NULL ,
									
									PRIMARY KEY (id)
								);
					");
	}

	public function down()
	{
		$this->db->pdo->exec("DROP TABLE IF EXISTS emailToken");
	}
}
