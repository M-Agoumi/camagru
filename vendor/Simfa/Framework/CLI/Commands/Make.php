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



namespace Simfa\Framework\CLI\Commands;

use Simfa\Framework\CLI\BaseCommand;
use Simfa\Framework\CLI\BaseCommandInterface;
use Simfa\Framework\CLI\CLIApplication;

class Make extends BaseCommand implements BaseCommandInterface
{
	public function __construct()
	{
		self::$command = 'make';
	}

	/** Make a Controller
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
	 * get the Controller name from the user
	 * @param $name
	 * @return array|string|string[]
	 */
	private function getControllerName($name) {
		if (!$name){
			echo "\x1b[32mMaking a Controller.. without a name? didn't think so ^_^\nYour Controller Name:\n\x1b[33m";
			$name = readline();
		}
		$name = ucfirst($name);
		if (!$this->endsWith($name, 'Controller') && !$this->endsWith($name, 'Controller'))
			$name .= "Controller";
		$name = str_replace('Controller', 'Controller', $name);
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


	/** make the Controller file
	 * @param $controllerName
	 */
	private function makeController($controllerName)
	{
		/** generate view file name */
		$viewFileName = substr(str_shuffle(md5(time())),0,8);
		/** make Controller Content */
		$controllerContent = $this->controllerContent($controllerName, $viewFileName);
		$controller = fopen("src/Controller/" . $controllerName . ".php", "w");

		/** write the controller content to the controller file */
		fwrite($controller, $controllerContent);
		fclose($controller);

		/** make view file for the specified controller */
		$this->makeView($viewFileName);

		/** create Route */
		$routesFile = CLIApplication::$app->root . 'routes/web.php';
		$route = "Simfa\Framework\Router::get('/" . $viewFileName . "', [\Controller\\" . $controllerName .  "::class, 'index']);";
		file_put_contents($routesFile, $route.PHP_EOL , FILE_APPEND | LOCK_EX);
		echo 'Controller created'. PHP_EOL;
		echo 'View created'. PHP_EOL;
		echo 'Route created'. PHP_EOL;
	}


	/** Generate the Controller content
	 * @param string $controllerName
	 * @param string $view
	 * @return string
	 */
	private function controllerContent(string $controllerName, string $view): string
	{
		/** @var string $template get class template and stop script in case it weren't found */
		$templateFileName = CLIApplication::$app->root . 'vendor/Simfa/Views/Commands/templates/controller.template';
		$template = file_exists($templateFileName) ? file_get_contents($templateFileName) : false;

		if (!$template)
			die(RED . 'an essential file for this command is not found. file name: ' . $templateFileName . PHP_EOL . RESET);

		/** generate file name and set it to the header */
		$classFileName = $controllerName . '.php' . str_repeat(' ', 50 - strlen($controllerName . '.php'));
		$template = str_replace('{{ file_name }}', $classFileName, $template);

		/** generate date for the header and set it in the template */
		$classHeaderTime = date("Y/m/d G:i:s");
		$template = str_replace('{{ date_time }}', $classHeaderTime, $template);

		/** set the class name */
		$template = str_replace('{{ class_name }}', $controllerName, $template);

		/** set the view file name */
		return str_replace('{{ view_name }}', "$view", $template);
	}

	public static function helper(string $command = 'make'): string
	{
		self::$command = $command;
		$helperMessage = RED . self::$command . RESET . PHP_EOL;
		$helperMessage .= self::printCommand('Controller') . "make a Controller (accepts Controller";
		$helperMessage .= " name as an argument)[make:Controller name]". PHP_EOL;
		$helperMessage .= self::printCommand('entity'). "make an entity(module) (accept entity name as an";
		$helperMessage .= "argument)[make:entity name]";

		return $helperMessage;
	}

	private function makeView($viewFileName)
	{
		$templateFileName = CLIApplication::$app->root . 'vendor/Simfa/Views/Commands/templates/default_view.template';
		$template = file_exists($templateFileName) ? file_get_contents($templateFileName) : false;

		if (!$template)
			die(RED . 'an essential file for this command is not found. file name: ' . $templateFileName . PHP_EOL . RESET);

		$view = fopen("views/templates/" . $viewFileName . ".gaster.php", "w");

		/** write the controller content to the controller file */
		fwrite($view, $template);
		fclose($view);
	}
}
