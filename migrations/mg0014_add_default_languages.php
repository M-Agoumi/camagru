<?php

use Simfa\Framework\Application;
use Simfa\Framework\Db\Database;
use Simfa\Model\Language;

class mg0014_add_default_languages
{
	public Database $db;

	public function __construct()
	{
		$this->db = Application::$APP->db;
	}

	public function up()
	{
		$languages = ['en', 'fr', 'ar'];

		foreach ($languages as $lang) {
			$language = new Language();
			$language->setLanguage($lang);
			$language->save();
		}
//		$this->db->pdo->exec("insert into languages (language, created_at) values
//                                                    ('en', CURRENT_TIMESTAMP()),
//                                                    ('fr', CURRENT_TIMESTAMP()),
//                                                    ('ar', CURRENT_TIMESTAMP());
//					");
	}

	public function down()
	{
		$this->db->pdo->exec("DELETE FROM language");
	}
}
