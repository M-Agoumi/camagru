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

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0001_creating_user_table
{
	public function up()
	{
		Migration::create('user', function(Schema $table) {
			$table->id();
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
		Migration::drop('user');
	}
}
