<?php


use core\Application;

class mg0010_create_roles_table
{
	public \core\Db\Database $db;

	public function __construct()
	{
		$this->db = Application::$APP->db;
	}

	public function up()
	{
		$this->db->pdo->exec("CREATE TABLE roles(
									 id          int unsigned NOT NULL AUTO_INCREMENT ,
									 user        int NOT NULL ,
									 super_admin tinyint NOT NULL default 0,
									 users       tinyint NOT NULL default 0,
									 posts       tinyint NOT NULL default 0,
									 comments    tinyint NOT NULL default 0,
									 likes       tinyint NOT NULL default 0,
									 promote     tinyint NOT NULL default 0,
									 created_at  timestamp NOT NULL default CURRENT_TIMESTAMP,
									 updated_at  timestamp NULL ,
									 created_by	 int NOT NULL ,
									 updated_by  int NOT NULL ,
									
									PRIMARY KEY (id),
									KEY fkIdx_115 (user),
									CONSTRAINT FK_114 FOREIGN KEY fkIdx_115 (user) REFERENCES users (id),
									KEY fkIdx_125 (updated_by),
									CONSTRAINT FK_124 FOREIGN KEY fkIdx_125 (updated_by) REFERENCES users (id),
                 					KEY fkIdx_135 (updated_by),
									CONSTRAINT FK_134 FOREIGN KEY fkIdx_135 (updated_by) REFERENCES users (id)
								);

								insert into roles (
								                   user, 
								                   super_admin, 
								                   users, 
								                   posts, 
								                   comments, 
								                   likes, 
								                   promote, 
								                   created_at, 
								                   created_by, 
								                   updated_by) values (1,1,1,1,1,1,1,CURRENT_TIMESTAMP,1,1);
					");
	}

	public function down()
	{
		$this->db->pdo->exec("DROP TABLE IF EXISTS roles");
	}
}
