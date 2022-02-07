<?php


use Simfa\Framework\Application;
use Simfa\Framework\Db\Migration;

class mg0007_creating_contact_us_table
{
	public Simfa\Framework\Db\Database $db;

	public function __construct()
	{
		$this->db = Application::$APP->db;
	}

	public function up()
	{
		$this->db->pdo->exec("CREATE TABLE IF NOT EXISTS `contact_us` (
						 `EntityID`         int NOT NULL AUTO_INCREMENT ,
						 `logged`     tinyint NOT NULL ,
						 `user`       int NULL ,
						 `email`      varchar(255) NULL ,
						 `title`      varchar(255) NOT NULL ,
						 `content`    tinytext NOT NULL ,
						 `ParentId`   int NULL ,
						 `status`     tinyint NOT NULL ,
						 `created_at` timestamp NOT NULL ,
						 `updated_at` timestamp NULL ,
						
						PRIMARY KEY (`EntityID`),
						KEY `fkIdx_78` (`user`),
						CONSTRAINT `FK_77` FOREIGN KEY `fkIdx_78` (`user`) REFERENCES `user` (`EntityID`),
						KEY `fkIdx_84` (`Parentid`),
						CONSTRAINT `FK_83` FOREIGN KEY `fkIdx_84` (`Parentid`) REFERENCES `contact_us` (`EntityID`)
						);
					");
	}

	public function down()
	{
		Migration::drop('contact_us');
//		$this->db->pdo->exec("DROP TABLE IF EXISTS contact_us");
	}
}
