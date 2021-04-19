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

namespace core;

use controller\Controller;
use core\Db\Database;
use core\Db\DbModel;
use Exception;
use models\User;

/**
 * Class Application don't forget to include your user class
 */
class Application
{
	public static string $ROOT_DIR;
	public static Application $APP;
	public static array $ENV;
	public string $userCLass;
	public Database $db;
	public ?Router $router = null;
	public ?Request $request = null;
	public ?Response $response = null;
	public ?controller $controller = null;
	public ?Session $session = null;
	public ?DbModel $user;
	public ?View $view;
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
		$this->userCLass = self::getEnvValue('userClass');
		$this->request = New Request();
		$this->response = New Response();
		$this->session = New Session();
		$this->router = New Router($this->request, $this->response);
		$this->db = New Database($this->getDatabaseConfig());
		$this->view = New View();
		// todo implement session save for language preference and add more languages to choose from
		$this->MainLang = $this->setLang()[0];
		$this->fallbackLang = $this->setLang()[1];
		$this->user = self::getUser();
	}
	
	private static function getUser()
	{
		/** @var  $this->userClass User */
		$primaryValue = self::$APP->session->get('user');
		if ($primaryValue) {
			$primaryKey = self::$APP->userCLass::primaryKey();
			return self::$APP->userCLass::findOne([$primaryKey =>  $primaryValue]);
		}
		return NULL;
	}
	
	/**
	 * @return bool
	 */
	public static function isGuest(): bool
	{
		return !self::$APP->user;
	}
	
	/**
	 * calling the resolver method to handle our request
	 */
	public function run()
	{
	    try {
            echo $this->router->resolve();
        }catch (Exception $e) {
	        $this->response->setStatusCode($e->getCode());
	        if ($this->getDotEnv()['env'] != 'dev')
	            echo $this->view->renderView('error/__' . $e->getCode(), ['e' => $e], ['title' => $e->getCode()]);
	        else
	        	echo $this->view->renderView('error/__error', ['e' => $e], ['title' => $e->getCode()]);
        }
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

    /**
     * get the setting in .env file
     * @return array
     */
	private function getDotEnv() : array
	{
		return file_exists(self::$ROOT_DIR . "/.env") ?
				parse_ini_file(self::$ROOT_DIR . "/.env") : [];
	}

    /**
     * @param $attr
     * @return string|null env value if it exists
     */
    public static function getEnvValue($attr)
	{
		return self::$ENV[$attr] ?? null;
	}

    /**
     * read database config file or return null
     * @return array|false
     */
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

    /** read language config file
     * @return array
     */
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
	
	/**
	 * @param string $name
	 * @return string
	 */
	public static function path(string $name): string
    {
    	return self::$APP->router->path($name);
    }
	
	public function login(DbModel $user)
	{
		$this->user = $user;
		$primaryKey = $user->primaryKey();
		$primaryValue = $user->{$primaryKey};
		$this->session->set('user', $primaryValue);
		$this->response->redirect('/');
	}
	
	public static function logout()
	{
		$token = Application::$APP->request->getBody()['token'] ?? '';
		if ($token == 123123) {
			Application::$APP->user = NULL;
			Application::$APP->session->remove('user');
		}
		Application::$APP->response->redirect('/');
	}
}