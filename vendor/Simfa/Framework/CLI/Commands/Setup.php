<?php

namespace Simfa\Framework\CLI\Commands;

use Simfa\Framework\CLI\CLIApplication;

class Setup extends \Simfa\Framework\CLI\BaseCommand implements \Simfa\Framework\CLI\BaseCommandInterface
{

	public function __construct()
	{
		self::$command = 'setup';
	}

	/**
	 * set up the mail catcher env
	 */
	public function mail()
	{
		$email = $this->getUserEmail(CLIApplication::$app->argv);
		shell_exec('sh bin/scripts/mailcatcher.sh ' . $email);
		$server = new Server();
		$status = $server->status(CLIApplication::$app->argv, 1);
		if ($status != 0)
			$server->restart();
	}

	/**
	 * help set up the env for the first run
	 */
	public function setup()
	{
		/** check if database config file exists */
		if (!file_exists('config/db.conf')) {
			$this->write("Couldn't find database config file, creating one...",'warning',true);
			$config = $this->getUserDatabaseConfig();
			$this->createDatabaseConfigFile($config);
			$this->write('database config file created', 'success', true);
		}
		$this->write('creating database if doesn\'t exist', 'warning', true);
		shell_exec('php bin/console migrate:create:database');
		$this->write('applying migrations', 'warning', true);
		$this->liveExecuteCommand('bin/console migrate:migrate');
		$this->write("all ready now, do you want to run a server in development mode?[y\\n]", 'success');
		$this->write('[no]', 'danger', true);

		$isMailcatcherInstalled = shell_exec('which mailcatcher');
		if (stristr($isMailcatcherInstalled, 'mailcatcher')) {
			$this->write('we detected you have mail catcher installed do you want to enable it? [Y\n][n]','', true);
			$this->write('(ignore if it is already working)','warning');
			$this->write('', 'info', true, false);
			$setupMail = readline();
			if ($setupMail == 'y' || $setupMail == 'Y') {
				$this->write('setting up mail, enter your email');
				$this->write('', 'info', true, false);
				$mail = readline();
				echo shell_exec('bin/console setup:mail ' . $mail);
			}
		}
		$this->write('you can always run a server see [bin/console server] for more information', 'warning', true);
		$start = readline();
		if ($start == 'y' || $start == 'Y')
			echo shell_exec('bin/console server:start');
	}

	/**
	 * get the user email from the arguments
	 * @param $parameters
	 * @return false|string
	 */
	private function getUserEmail($parameters)
	{
		if (isset($parameters[1]) && $parameters[1])
			return $parameters[1];

		$confirmation = 'n';
		$email = 'example@email.com';

		while (!($confirmation == 'Y' || $confirmation == 'y')) {
			$this->write('please provide application email:');
			$this->write('', 'info', true, false);
			$email = readline();
			$this->write('your email is ' . $email . '? [Y\n]', 'warning');
			$this->write('[n]', 'danger');
			$this->write(' ', 'info', true, false);
			$confirmation = readline();
		}
		$this->write('address email confirmed', 'success', true);

		return $email;
	}

	/** get the user database config
	 * @return array
	 */
	private function getUserDatabaseConfig(): array
	{
		$this->write('please provide your database host [127.0.0.1]:');
		$this->write('', 'info', true, false);
		$host = readline();

		$this->write('please provide your database port [3306]:');
		$this->write('', 'info', true, false);
		$port = readline();

		$this->write('please provide your database name:');
		$this->write('', 'info', true, false);
		$dbname = readline();

		$this->write('please provide your database user [username]:');
		$this->write('', 'info', true, false);
		$username = readline() ?? 'username';

		$this->write('please provide your database password [password]:');
		$this->write('', 'info', true, false);
		$password = readline() ?? 'password';

		return [
			'host' => !empty($host) ? $host : '127.0.0.1',
			'port' => !empty($port) ? $port : '3306',
			'dbname' => $dbname,
			'username' => !empty($username) ? $username : 'username',
			'password' => !empty($password) ? $password : 'password'
		];
	}

	/** create database config file */
	private function createDatabaseConfigFile(array $config)
	{
		$fileContent = "DB_DSN = \"mysql:host=" . $config['host'] . ";port=" . $config['port'] .
			";dbname=" . $config['dbname'] . '"' .PHP_EOL;
		$fileContent .= "DB_USER = " . $config['username'] . PHP_EOL;
		$fileContent .= "DB_PASSWORD = " . $config['password'] . PHP_EOL;

		$configFile = fopen("config/db.conf", "w");
		fwrite($configFile, $fileContent);
	}


	/**
	 * get a live shell command execution outputs
	 * @param $cmd
	 * @return array
	 */
	private function liveExecuteCommand($cmd)
	{

		while (@ ob_end_flush()); // end all output buffers if any

		$proc = popen("$cmd 2>&1 ; echo Exit status : $?", 'r');

		$live_output     = "";
		$complete_output = "";

		while (!feof($proc))
		{
			$live_output     = fread($proc, 4096);
			$complete_output = $complete_output . $live_output;
			echo "$live_output";
			@ flush();
		}

		pclose($proc);

		// get exit status
		preg_match('/[0-9]+$/', $complete_output, $matches);

		// return exit status and intended output
		return array (
			'exit_status'  => intval($matches[0]),
			'output'       => str_replace("Exit status : " . $matches[0], '', $complete_output)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function helper($command = 'setup'): string
	{
		self::$command = $command;
		$helperMessage = RED . self::$command . RESET . PHP_EOL;
		$helperMessage .= self::printCommand('mail') . "setup your mail server, so you can send mails" . PHP_EOL;
		$helperMessage .= self::printCommand('setup') . "setup your local env, you should run ";
		$helperMessage .= "this command if you just installed the app" . PHP_EOL;

		return $helperMessage;
	}
}
