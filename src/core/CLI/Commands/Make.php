<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   Make.php                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: magoumi <magoumi@student.1337.m            +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2021/10/20 17:41:50 by magoumi           #+#    #+#             */
/*   Updated: 2021/10/20 17:41:50 by magoumi          ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */



namespace core\CLI\Commands;

use core\CLI\BaseCommand;
use core\CLI\BaseCommandInterface;

class Make extends BaseCommand implements BaseCommandInterface
{
	public function __construct()
	{
		self::$command = 'make';
	}

	/** Make a controller
	 * @param $argv
	 */
	public function controller($argv): void
	{
		$controllerName = $this->getControllerName($argv[1] ?? '');
		$this->makeController($controllerName);
	}

	/**
	 * make an entity
	 * @param $argv
	 */
	public function entity($argv): void
	{
		$entity = new MakeEntity();

		$entity->entity($argv);
	}

	/**
	 * get the controller name from the user
	 * @param $name
	 * @return array|string|string[]
	 */
	private function getControllerName($name) {
		if (!$name){
			echo "\x1b[32mMaking a Controller.. without a name? didn't think so ^_^\nYour Controller Name:\n\x1b[33m";
			$name = readline();
		}
		$name = ucfirst($name);
		if (!$this->endsWith($name, 'Controller') && !$this->endsWith($name, 'controller'))
			$name .= "Controller";
		$name = str_replace('controller', 'Controller', $name);
		echo "\x1b[32m" . $name . PHP_EOL;
		return $name;
	}


	/** check if a $haystack ends with a $needle
	 * @param $haystack
	 * @param $needle
	 * @return bool
	 */
	private function endsWith($haystack, $needle): bool
	{
		$length = strlen($needle);
		if (!$length)
			return True;
		return substr($haystack, -$length) === $needle;
	}


	/** make the controller file
	 * @param $controllerName
	 */
	private function makeController($controllerName)
	{
		echo "making $controllerName" . PHP_EOL;
		$controllerContent = $this->controllerContent($controllerName);

		$controller = fopen("src/controller/" . $controllerName . ".php", "w");

		fwrite($controller, $controllerContent);
		fclose($controller);
		echo 'Controller created'. PHP_EOL;
	}


	/** Generate the controller content
	 * @param $controllerName
	 * @return string
	 */
	private function controllerContent($controllerName): string
	{
		$time = date("Y/m/d H:i:s");;
		$content = '<?php' . PHP_EOL;
		$content .= '/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   ' . $controllerName . '.php                                :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: ' . $time . ' by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: ' . $time . ' by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */
';
		$content .= PHP_EOL . 'namespace controller;' . PHP_EOL . PHP_EOL;
		$content .= 'class ' . $controllerName . ' extends Controller' . PHP_EOL;
		$content .= '{' . PHP_EOL . PHP_EOL;
		$content .= '   public function __construct()' . PHP_EOL;
		$content .= '   {' . PHP_EOL . '        // TODO implement your controller' . PHP_EOL . '   }' . PHP_EOL . '}' . PHP_EOL;

		return $content;
	}

	public static function helper(string $command = 'make'): string
	{
		self::$command = $command;
		$helperMessage = RED . self::$command . RESET . PHP_EOL;
		$helperMessage .= self::printCommand('controller') . "make a controller (accepts controller";
		$helperMessage .= " name as an argument)[make:controller name]";

		return $helperMessage;
	}
}
