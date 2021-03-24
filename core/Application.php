<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   Application.php                                   :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/03/17 09:11:16 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/03/17 09:11:16 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */


require_once 'Router.php';
require_once 'Request.php';
require_once 'Response.php';
require_once 'Database.php';
require_once '../controller/Controller.php';

/**
 * Class Application
 */
class Application
{
	public static string $ROOT_DIR;
	public static Application $APP;
	public static array $ENV;
	public Database $db;
	public ?Router $router = null;
	public ?Request $request = null;
	public ?Response $response = null;
	public ?Controller $controller = null;
	protected array $MainLang = [];
	protected array $fallbackLang = [];

	/**
	 * Application constructor.
	 * @param $rootPath string the root path of our application
	 */

	public function __construct(string $rootPath)
	{
		self::$ROOT_DIR = $rootPath;
		self::$APP = $this;
		self::$ENV = $this->getDotEnv();
		$this->request = New Request();
		$this->response = New Response();
		$this->router = New Router($this->request, $this->response);
		$this->db = New Database($this->getDatabaseConfig());
		// todo implement session save for language preference and add more languages to choose from
		$this->MainLang = $this->setLang()[0];
		$this->fallbackLang = $this->setLang()[1];
	}

	/**
	 * calling the resolver method to handle our request
	 */
	public function run()
	{
		echo $this->router->resolve();
	}

	/**
	 * @return Controller|null
	 */
	public function getController(): ?Controller
	{
		return $this->controller;
	}

	/**
	 * @param Controller|null $controller
	 */
	public function setController(?Controller $controller): void
	{
		$this->controller = $controller;
	}

	private function getDotEnv()
	{
		return file_exists(self::$ROOT_DIR . "/.env") ?
				parse_ini_file(self::$ROOT_DIR . "/.env") : [];
	}

	public function getEnvValue($attr)
	{
		return SELF::$ENV[$attr] ?? null;
	}

	public function getDatabaseConfig()
	{
		return file_exists(self::$ROOT_DIR . "/config/db.conf") ?
				parse_ini_file(self::$ROOT_DIR . "/config/db.conf") : [];
	}


    /**
     * @param string $key
     * @return string
     */
    public function lang(string $key): string
    {
	    return $this->MainLang[$key] ?? $this->fallbackLang[$key] ?? $key;
    }

    private function setLang(): array
    {
        $lang = [];
		// todo handle the case when one of those files is not presented (include fail)
        $config = parse_ini_file(self::$ROOT_DIR . "/config/lang.conf");
        // todo check session if the preferenced lang is stored use it otherwise take it from the config and set it to the session
        array_push($lang, include self::$ROOT_DIR . '/translation/' . $config['main_language'] . '.lang.php');
        array_push($lang, include self::$ROOT_DIR . '/translation/' . $config['fallback_language'] . '.lang.php');
        return $lang;
    }
}