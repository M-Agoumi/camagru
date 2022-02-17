<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   SimpleData.php                                     :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: magoumi <magoumi@student.1337.m            +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2022/02/6 18:38:44 by magoumi            #+#    #+#             */
/*   Updated: 2022/02/6 18:38:44 by magoumi           ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */


namespace Command;

use Model\Role;
use Model\User;
use Simfa\Framework\CLI\BaseCommand;
class Admin extends BaseCommand
{
	protected static string $command = 'admin';

	public function admin($argv)
	{
		if (isset($argv[1])) {
			$user = New User();
			$user->getOneBy('username', $argv[1]);

			if (!$user->getId())
				return YELLOW . "User with such a username doesn't exist" . RESET . PHP_EOL;
			$role = new Role();
			$role->getOneBy('user', $user->getId());
			if ($role->getId())
				return RED . "User is already an admin" . PHP_EOL . RESET;

			$role->setUser($user);
			$role->super_admin = 1;
			$role->setUsers(1);
			$role->setPosts(1);
			$role->setComments(1);
			$role->setLikes(1);
			$role->setPromote(1);
			$role->setMail(1);
			$role->created_by = $user;
			$role->updated_by = $user;
			$role->created_at = date('Y-m-d H:i:s', time());

			if ($role->save())
				return GREEN . "User has been granted Admin Access" . RESET . PHP_EOL;
			else
				return RED . "Error encountered while promoting the user". RESET . PHP_EOL;
		}

		return MAGENTA . "Please Provide An Username" . PHP_EOL . RESET;
	}

	public static function helper(): string
	{
		$helperMessage  = RED . self::$command . RESET . PHP_EOL;
		$helperMessage .= self::printCommand('admin') . "give admin permission to user by username" . PHP_EOL;

		return $helperMessage;
	}
}
