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

use core\Application;

class mg0001_creating_user_table
{
	public function up()
	{
		$db = Application::$APP->db;

		$db->pdo->exec("CREATE TABLE `users` (
							  `id` int NOT NULL AUTO_INCREMENT,
							  `email` varchar(255) NOT NULL,
							  `name` varchar(255) DEFAULT NULL,
							  `username` varchar(255) DEFAULT NULL,
							  `password` varchar(255) DEFAULT NULL,
							  `status` tinyint NOT NULL DEFAULT '0',
							  `picture` varchar(255) DEFAULT NULL,
							  `ip_address` varchar(45) DEFAULT NULL,
							  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
							  `updated_at` timestamp NULL DEFAULT NULL,
							  PRIMARY KEY (`id`)
						) ENGINE=InnoDB");
	}

	public function down()
	{
		$db = Application::$APP->db;

		$db->pdo->exec("DROP TABLE users");
	}
}
