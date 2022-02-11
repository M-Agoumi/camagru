<?php

use Simfa\Framework\Application;
use Simfa\Framework\Db\Database;
use Simfa\Model\Language;

class mg0014_add_default_languages
{
	public function up()
	{
		$languages = ['en', 'fr', 'ar'];

		foreach ($languages as $lang) {
			$language = new Language();
			$language->setLanguage($lang);
			$language->save();
		}
	}

	public function down()
	{

	}
}
