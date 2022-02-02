<?php

namespace Command;

use Simfa\Framework\CLI\BaseCommand;
use Simfa\Framework\CLI\BaseCommandInterface;

class SimpleData extends BaseCommand implements BaseCommandInterface
{

	public function __construct()
	{
		self::$command = 'simpleData';
	}

	public function posts()
	{
		die("we are on\n");
	}

	public static function helper(string $command = 'simpleData'): string
	{
		$helperMessage  = RED . self::$command . RESET . PHP_EOL;
		$helperMessage .= self::printCommand('posts') . "create random posts" . PHP_EOL;
		$helperMessage .= CYAN ."     -v --visual" . RESET . "\t\tprint created posts files";

		return $helperMessage;
	}
}
