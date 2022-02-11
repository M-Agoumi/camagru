<?php


use Simfa\Framework\Application;
use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0010_create_roles_table
{
//	public Simfa\Framework\Db\Database $db;
//
//	public function __construct()
//	{
//		$this->db = Application::$APP->db;
//	}

	public function up()
	{
//		$this->db->pdo->exec("CREATE TABLE role(
//									 id          int unsigned NOT NULL AUTO_INCREMENT ,
//									 user        int NOT NULL ,
//									 super_admin tinyint NOT NULL default 0,
//									 users       tinyint NOT NULL default 0,
//									 posts       tinyint NOT NULL default 0,
//									 comments    tinyint NOT NULL default 0,
//									 likes       tinyint NOT NULL default 0,
//									 promote     tinyint NOT NULL default 0,
//									 created_at  timestamp NOT NULL default CURRENT_TIMESTAMP,
//									 updated_at  timestamp NULL ,
//									 created_by	 int NOT NULL ,
//									 updated_by  int NOT NULL ,
//
//									PRIMARY KEY (id),
//									KEY fkIdx_115 (user),
//									CONSTRAINT FK_114 FOREIGN KEY fkIdx_115 (user) REFERENCES user (EntityID),
//									KEY fkIdx_125 (updated_by),
//									CONSTRAINT FK_124 FOREIGN KEY fkIdx_125 (updated_by) REFERENCES `user` (EntityID),
//                 					KEY fkIdx_135 (updated_by),
//									CONSTRAINT FK_134 FOREIGN KEY fkIdx_135 (updated_by) REFERENCES `user` (EntityID)
//								);
//					");
		Migration::create('role', function (Schema $table){
			$table->id();
			$table->int('user');
			$table->smallInt('super_admin')->default(0);
			$table->smallInt('users')->default(0);
			$table->smallInt('posts')->default(0);
			$table->smallInt('comments')->default(0);
			$table->smallInt('likes')->default(0);
			$table->smallInt('promote')->default(0);
			$table->int('created_by');
			$table->int('updated_by');
			$table->smallInt('mail');
			$table->timestamps();
			$table->foreign('user')->references('entityID')->on('user')->onUpdate('cascade')
				->onDelete('cascade');
			$table->foreign('created_by')->references('entityID')->on('user')->onUpdate('cascade')
				->onDelete('cascade');
			$table->foreign('updated_by')->references('entityID')->on('user')->onUpdate('cascade')
				->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop('role');
//		$this->db->pdo->exec("DROP TABLE IF EXISTS roles");
	}
}
