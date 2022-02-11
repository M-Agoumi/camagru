<?php

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0007_creating_contact_us_table
{
	public function up()
	{
//		$this->db->pdo->exec("CREATE TABLE IF NOT EXISTS `contact_us` (
//						 `EntityID`         int NOT NULL AUTO_INCREMENT ,
//						 `logged`     tinyint NOT NULL ,
//						 `user`       int NULL ,
//						 `email`      varchar(255) NULL ,
//						 `title`      varchar(255) NOT NULL ,
//						 `content`    tinytext NOT NULL ,
//						 `ParentId`   int NULL ,
//						 `status`     tinyint NOT NULL ,
//						 `created_at` timestamp NOT NULL ,
//						 `updated_at` timestamp NULL ,
//
//						PRIMARY KEY (`EntityID`),
//						KEY `fkIdx_78` (`user`),
//						CONSTRAINT `FK_77` FOREIGN KEY `fkIdx_78` (`user`) REFERENCES `user` (`EntityID`),
//						KEY `fkIdx_84` (`Parentid`),
//						CONSTRAINT `FK_83` FOREIGN KEY `fkIdx_84` (`Parentid`) REFERENCES `contact_us` (`EntityID`)
//						);
//					");
		Migration::create('contact_us', function (Schema $table) {
			$table->id();
			$table->smallInt('logged');
			$table->int('user');
			$table->string('email')->nullable();
			$table->string('title');
			$table->int('ParentId')->nullable();
			$table->text('content');
			$table->smallInt('status')->default(0);
			$table->timestamps();
			$table->foreign('user')->references('entityID')->on('user')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('ParentId')->references('entityID')->on('contact_us')->onUpdate('cascade')->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('contact_us');
	}
}
