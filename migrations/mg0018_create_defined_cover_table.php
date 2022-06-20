<?php

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0018_create_defined_cover_table
{
	public function up()
	{
		Migration::create('cover', function (Schema $table){
			$table->id();
			$table->string('name');
			$table->string('image');
			$table->timestamps();

			return $table;
		});

		$defined_images = ['shores' => 'shores.jpg', 'the calling' => 'the_calling.png', 'so near and so far' => 'milky_way.jpg'];
		foreach ($defined_images as $key => $value) {
			$cover = new \Model\Cover();
			$cover->setName($key);
			$cover->setImage($value);
			$cover->save();
		}
	}

	public function down()
	{
		Migration::drop('cover');
	}
}
