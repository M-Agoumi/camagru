<?php


use core\Application;
use core\Db\Database;

class mg0014_add_default_languages
{
	public Database $db;

	public function __construct()
	{
		$this->db = Application::$APP->db;
	}

	public function up()
	{
		$this->db->pdo->exec("insert into languages (language, created_at) values
                                                    ('en', CURRENT_TIMESTAMP()),
                                                    ('fr', CURRENT_TIMESTAMP()),
                                                    ('ar', CURRENT_TIMESTAMP());
					");
	}

	public function down()
	{
		$this->db->pdo->exec("DELETE FROM languages");
	}
}
