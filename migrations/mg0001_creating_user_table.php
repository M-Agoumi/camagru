<?php
# **************************************************************************** #
#                                                                              #
#                                                         :::      ::::::::    #
#    mg0001_creating_user_table.php                     :+:      :+:    :+:    #
#                                                     +:+ +:+         +:+      #
#    By: magoumi <agoumi.mohamed@outlook.com>       +#+  +:+       +#+         #
#                                                 +#+#+#+#+#+   +#+            #
#    Created: 2021/03/21 19:05:23 by magoumi           #+#    #+#              #
#    Updated: 2021/03/21 19:05:23 by magoumi          ###   ########lyon.fr    #
#                                                                              #
# **************************************************************************** #

use Simfa\Framework\Application;
use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0001_creating_user_table
{
	public function up()
	{
		$db = Application::$APP->db;

//		$db->pdo->exec("CREATE TABLE `users` (
//							  `id` int NOT NULL AUTO_INCREMENT,
//							  `email` varchar(255) NOT NULL,
//							  `name` varchar(255) DEFAULT NULL,
//							  `username` varchar(255) DEFAULT NULL,
//							  `password` varchar(255) DEFAULT NULL,
//							  `status` tinyint NOT NULL DEFAULT '0',
//							  `picture` varchar(255) DEFAULT NULL,
//							  `ip_address` varchar(45) DEFAULT NULL,
//							  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
//							  `updated_at` timestamp NULL DEFAULT NULL,
//							  PRIMARY KEY (`id`)
//						) ENGINE=InnoDB;
//						--password:password
//
//						");
		Migration::create('user', function(Schema $table) {
			$table->id();
			$table->string('email');
			$table->string('name')->nullable();
			$table->string('username')->nullable();
			$table->string('password')->nullable();
			$table->smallInt('status')->default(0);
			$table->string('picture')->nullable();
			$table->string('ip_address')->nullable();
			$table->timestamps();

			return $table;
		});

	}

	public function down()
	{
//		$db = Application::$APP->db;
//
//		$db->pdo->exec("DROP TABLE users");
		Migration::drop('user');
	}
}
