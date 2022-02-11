<?php


use Simfa\Framework\Application;
use Simfa\Framework\Db\Database;
use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0012_create_user_login_token_table
{
//	public Database $db;
//
//	public function __construct()
//	{
//		$this->db = Application::$APP->db;
//	}

	public function up()
	{
//		$this->db->pdo->exec("CREATE TABLE user_token(
//									id         int unsigned NOT NULL AUTO_INCREMENT ,
//									user       int NOT NULL ,
//									token      varchar(128) NOT NULL ,
//									used       tinyint NOT NULL default 0,
//									created_at timestamp NOT NULL ,
//									updated_at timestamp NULL ,
//
//									PRIMARY KEY (id),
//									KEY fkIdx_131 (user),
//									CONSTRAINT FK_130 FOREIGN KEY fkIdx_131 (user) REFERENCES user (EntityID) ON DELETE CASCADE
//								);
//					");
		Migration::create('user_token', function (Schema $table){
			$table->id();
			$table->int('user');
			$table->string('token');
			$table->smallInt('used')->default(0);
			$table->timestamps();
			$table->foreign('user')->references('entityID')->on('user')->onUpdate('cascade')
				->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('user_token');
//		$this->db->pdo->exec("DROP TABLE IF EXISTS user_token");
	}
}
