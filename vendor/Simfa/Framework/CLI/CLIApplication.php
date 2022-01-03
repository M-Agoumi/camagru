<?php

namespace Simfa\Framework\CLI;

use Simfa\Framework\CLI\Commands\BaseCommands;
use Simfa\Framework\CLI\Commands\Cache;
use Simfa\Framework\CLI\Commands\Make;
use Simfa\Framework\CLI\Commands\Migrate;
use Simfa\Framework\CLI\Commands\Server;
use Simfa\Framework\CLI\Commands\Setup;

class CLIApplication
{
	public static CLIApplication $app;
	public int $argc;
	public array $argv;
	public string $root;
	public array $commands;
	public bool $quite = false;

	/** saving our important values for our commands
	 * @param string $root
	 * @param int $argc
	 * @param array $argv
	 */
	public function __construct(string $root, int $argc, array $argv)
	{
		self::$app = $this;
		array_shift($argv);
		$this->argc = $argc - 1;
		$this->argv = $argv;
		$this->root = $root . '/';
		$this->commands = $this->registerCommands();

		if (PHP_SAPI !== 'cli')
			die('bin/console must be run as a CLI application' . PHP_EOL);
	}

	/** our App heart
	 * @return string
	 */
	public function run(): string
	{
		/** no command? show help */
		if (!$this->argc)
			return $this->helper();

		$command = explode(':', $this->argv[0]);
		$command = array_map('strtolower', $command);

		/** check for quite mode */
		if (in_array('-q', $this->argv) || in_array('--quite', $this->argv)) {
			$this->quite = true;
		}

		/** check if the command is a base command */
		if (in_array($command[0], $this->baseCommands()))
			return $this->runBaseCommand($command[0]);

		if (isset($this->commands[$command[0]])) {
			if (isset($command[1])) {
				/** run command */
				$CommandInstance = new $this->commands[$command[0]]($command[0]);

				/** check if the wanted command exists in our class, and it's public, then run it */
				if (is_callable(array($CommandInstance, $command[1]))){
					$output = $CommandInstance->{$command[1]}($this->argv);

					return !$this->quite ? is_string($output) ? $output : '' : '';
				} else {
					$message = RED . $command[1] . RESET . " doesn't exist in " . BLUE . $command[0] . RESET . PHP_EOL;
					$message .= 'Available commands:' . PHP_EOL;
					$message .= $this->commands[$command[0]]::helper() . PHP_EOL;

					return $message;
				}

			} else {
				/** show command helper */
				return $this->commands[$command[0]]::helper() . PHP_EOL;
			}
		}

		return RED . $command[0] . RESET . " command not found\nAvailable commands:\n\n" . $this->helper();
	}

	/** display the commands help instruction
	 * @return string
	 */
	private function helper():string
	{
		$helpers = $this->commands;
		$message = $this->helpMessage();
		$first = '';

		foreach ($helpers as $command => $class) {
			$message .= $first . $class::helper($command) . PHP_EOL;
			$first = PHP_EOL;
		}

		return $message;
	}

	/** registered commands AKA the activated commands
	 * @return string[]
	 */
	private function registerCommands():array
	{
		return [
			'cache'     => Cache::class,
			'make'      => Make::class,
			'migrate'   => Migrate::class,
			'server'    => Server::class,
			'setup'     => Setup::class
		];
	}

	/**
	 * base commands
	 */
	private function baseCommands():array
	{
		return [
			'--help',
			'-h',
			'--version',
			'-v',
			'up',
			'down'
		];
	}


	/** display the help instruction of the application
	 * @return string
	 */
	private function helpMessage(): string
	{
		$message = CYAN . 'Usage:' . RESET . PHP_EOL;
		$message .= '  bin/console [options] [arguments]' . PHP_EOL . PHP_EOL;
		$message .= CYAN . 'Options:' . RESET . PHP_EOL;
		$message .= "  -h, --help\t\tDisplay this help message" . PHP_EOL;
		$message .= "  -q, --quite\t\tDo not output any messages" . PHP_EOL;
		$message .= "  -v, --version\t\tDisplay this application version" .PHP_EOL;
		$message .= "  down\t\t\tPut the server in maintenance mode". PHP_EOL;
		$message .= "       \t\t\tAccepts allowed IPs to access the Application[console down ip1 ip2 ip3]" . PHP_EOL;
		$message .= "  up\t\t\tDisable maintenance mode and restore the Application online" . PHP_EOL . PHP_EOL;

		return $message;
	}

	private function runBaseCommand($command): string
	{
		$base = New BaseCommands();

		if ($command == '-v' || $command == '--version')
			return $base->version();
		else if ($command == '-h' || $command == '--help')
			return $this->helper();
		elseif ($command == 'down')
			return $base->down();
		elseif ($command == 'up')
			return $base->up();

		return '';
	}
}
