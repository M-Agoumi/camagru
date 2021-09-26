<?php

namespace core\CLI\Commands;

use core\CLI\CLIApplication;

class BaseCommands extends \core\CLI\BaseCommand implements \core\CLI\BaseCommandInterface
{

	/**
	 * @inheritDoc
	 */
	public function __construct()
	{
	}

	/**
	 * @return string
	 */
	public function version(): string
	{
		return GREEN . 'VERSION: ' . CYAN . '1.0.0' . RESET . PHP_EOL;
	}


	public function down(): string
	{
		$downFile = CLIApplication::$app->root . 'var/cache/maintenance_on';
		$allowed = '';

		/** check if server is already down */
		if (file_exists($downFile))
			$allowed = unserialize(trim(file_get_contents($downFile)));

		if (isset(CLIApplication::$app->argv[1]) && $allowed == '')
			$allowed = serialize(array_slice(CLIApplication::$app->argv, 1));
		else if (isset(CLIApplication::$app->argv[1]) && $allowed != '') {
			$allowed = array_merge($allowed, array_slice(CLIApplication::$app->argv, 1));
			$allowed = serialize($allowed);
		}

		$fd = fopen($downFile, "w");

		if ($fd && fputs($fd, $allowed))
			return YELLOW . "SERVER NOW IS ON MAINTENANCE MODE" . RESET . PHP_EOL;

		return RED . "SOMETHING WENT WRONG WHILE PUTTING THE SERVER ON MAINTENANCE MODE" . RESET . PHP_EOL;

	}


	public function up(): string
	{
		$downFile = CLIApplication::$app->root . 'var/cache/maintenance_on';

		if (unlink($downFile))
			return YELLOW . "SERVER MAINTENANCE MODE HAS DEACTIVATED" . RESET . PHP_EOL;

		return RED . "SOMETHING WENT WRONG WHILE PUTTING THE SERVER OFF MAINTENANCE MODE" . RESET . PHP_EOL;
	}


	/**
	 * @inheritDoc
	 */
	public static function helper(string $command): string
	{
		return '';
	}

}
