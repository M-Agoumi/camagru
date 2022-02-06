<?php

namespace Simfa\Framework\CLI\Commands;

use Simfa\Framework\CLI\CLIApplication;

class Server extends \Simfa\Framework\CLI\BaseCommand implements \Simfa\Framework\CLI\BaseCommandInterface
{

	/**
	 * @var string
	 */
	protected static string $command = 'server';

	/** start a dev server
	 * @param array $argv
	 */
	public function start(array $argv)
	{
		$port = $this->getPort($argv);
		$this->serverStartNoFail($port);
	}

	/**
	 * stop a running dev server
	 */
	public function stop()
	{
		$this->serverStop();
	}

	/** get the status of the server [running/stopped]
	 * @param $argv
	 * @param bool $cli
	 * @return false|int|string|void|null
	 */
	public function status($argv, bool $cli = false)
	{
		$port = $this->serverStatus();
		if ($cli)
			return $port;
		if ($port)
			echo YELLOW . 'server is running on port ' . GREEN . $port . RESET;
		else
			echo RED . 'server is not running' . RESET . PHP_EOL;
	}

	/**
	 * restart a running server (also keeps the original port)
	 */
	public function restart()
	{
		$this->serverRestart();
	}

	/** get the logs of server (supports live logs)
	 * @return string|void
	 */
	public function log()
	{
		$server = $this->serverStatus();
		if ($server)
			echo YELLOW . "server is running on http://localhost:" . CYAN . $server . RESET;
		else
			echo RED . "server is not running" . RESET . PHP_EOL;

		if (CLIApplication::$app->argc > 1) {
			if (in_array('-f', CLIApplication::$app->argv) || in_array('--follow', CLIApplication::$app->argv)) {
				$file = "runtime/logs/server.log";
				$last_modify_time = 0;
				$first_time = true;
				while (true) {
					sleep(1); // 1 s
					clearstatcache(true, $file);
					$curr_modify_time = filemtime($file);
					if ($last_modify_time < $curr_modify_time) {
						if ($first_time)
							$first_time = false;
						else
							echo file_get_contents($file);
					}

					$last_modify_time = $curr_modify_time;
				}
			} else
				return RED . "Parameter is unknown" . RESET . PHP_EOL;
		}
		$file = file("runtime/logs/server.log");
		for ($i = max(0, count($file)-11); $i < count($file); $i++) {
			echo $file[$i];
		}
	}

	/** start a server if port is unavailable tries next one
	 * @param $port
	 */
	private function serverStartNoFail($port) {
		/** keep trying till it work */
		while (1) {
			// check if there is a running server already
			if ($this->serverStatus())
				die("Can't run server, server is already running.." . PHP_EOL);

			echo "Starting a development server on http://localhost:" . $port . PHP_EOL;
			$server_status = $this->serverStart($port);

			if ($server_status === NULL) {
				echo B_RED . "Starting a server on port " . YELLOW . $port . RESET . " failed, port already used" .
					B_RESET . PHP_EOL;
				$port = intval($port) + 1;
				$port = $port < 3000 ? 3000 : $port;
			} else {
				break ;
			}
		}
	}

	/** gets the status of the server
	 * @return false|int|string|null
	 */
	private function serverStatus() {
		if (file_exists('var/server-app.pid')) {
			$port = shell_exec("sh bin/scripts/server_status.sh");
			if ($port != "0\n")
				return $port;
			else
				unlink('var/server-app.pid');
		}

		return 0;
	}

	/**
	 * stop the dev server if it's running
	 */
	private function serverStop() {
		if (file_exists('var/server-app.pid')) {
			if (shell_exec("sh bin/scripts/server_status.sh") != "0\n") {
				echo YELLOW . "Stopping server.." . RESET . PHP_EOL;
				shell_exec('sh bin/scripts/server_stop.sh');
			} else {
				unlink('var/server-app.pid');
				echo RED . "There is no running server.." . RESET . PHP_EOL;
			}
		} else
			echo RED . "There is no running server" . RESET . PHP_EOL;
	}

	/**
	 * restart the server or just start it if it's already stopped
	 */
	private function serverRestart() {
		$port = $this->serverStatus();
		if ($port) {
			echo YELLOW . "stopping server\n" . RESET;
			shell_exec('sh bin/scripts/server_stop.sh');
		} else {
			$port = 8000;
		}

		$this->serverStartNoFail($port);
	}

	/** server start sh command
	 * @param int $port
	 * @return false|int|string|null
	 */
	private function serverStart(int $port = 8000)
	{
		shell_exec('sh bin/scripts/server_start.sh ' . $port);

		return($this->serverStatus());
	}


	/**
	 * @inheritDoc
	 */
	public static function helper(): string
	{
		$helperMessage  = RED . self::$command . RESET . PHP_EOL;
		$helperMessage .= self::printCommand("start") . "start a php server on default ";
		$helperMessage .= "port 8000 unless another port has been specified" . PHP_EOL;
		$helperMessage .= CYAN ."      -p --port" . RESET . "\t\t\tfollowed by the port [-p 8080]" . PHP_EOL;
		$helperMessage .= self::printCommand("status") . "see server status and on which port it's running" . PHP_EOL;
		$helperMessage .= self::printCommand("stop") . "stop the running server" . PHP_EOL;
		$helperMessage .= self::printCommand("restart") . "restart server ps:will keep the same port" . PHP_EOL;
		$helperMessage .= self::printCommand("log") . "get php server logs" . PHP_EOL;
		$helperMessage .= CYAN ."      -f --follow" . RESET ."\t\tkeep watching outputting logs";
		
		return $helperMessage;
	}

	/** get the port from the arguments if there is
	 * @param $argv
	 * @return int|string
	 */
	private function getPort($argv)
	{
		$key = array_search('-p', $argv);
		$key = !$key ? array_search('--port', $argv) : $key;

		if (!is_numeric($key))
			return ("8000");
		else
			return $argv[$key + 1] ?? 8000;
	}
}
