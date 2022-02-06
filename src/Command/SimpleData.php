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

use Simfa\Framework\CLI\BaseCommand;

class SimpleData extends BaseCommand
{
	protected static string $command = 'simpleData';

	public function posts()
	{
		die("we are on\n");
	}

	public static function helper(): string
	{
		$helperMessage  = RED . self::$command . RESET . PHP_EOL;
		$helperMessage .= self::printCommand('posts') . "create random posts" . PHP_EOL;
		$helperMessage .= CYAN ."     -v --visual" . RESET . "\t\tprint created posts files";

		return $helperMessage;
	}
}
