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
use models\core\languages;
use models\core\preferences;
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
	public ?preferences $preferences = null;
	public ?DbModel $user;
	public ?View $view;
	public array $MainLang = [];
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
		$this->user = self::getUser();
		$this->preferences = $this->user ? preferences::getPerf($this->user->getId()) : null;
		$lang = $this->setLang();
		$this->MainLang = $lang[0];
		$this->fallbackLang = $lang[1];
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

	public static function isAppProperty($instance)
	{
		$rc = new \ReflectionClass(Application::class);

		$properties = $rc->getProperties();
		foreach ($properties as $property) {
			if ($instance == $property->name)
				return true;
		}

		unset($rc);
		return false;
	}

	/**
	 * calling the resolver method to handle our request
	 */
	public function run()
	{
	    try {
            echo $this->router->resolve();
        }catch (Exception $e) {
//	    	echo "<pre>";
//	    	var_dump($e);
//		    print_r($e->getTraceAsString());
//		    $file = $e->getFile();
//		    $lines = file($file);//file in to an array
//		    $line = $e->getLine();
//		    echo "<br>code:<br>" . ($line - 1) . "\t" .
//			    $lines[$line - 1] . "<br>$line\t" .
//			    $lines[$line ] . "<br>" . ($line + 1) . "\t" .
//			    $lines[$line + 1];
//		    echo "<br><br>on file " . $file . " line " . $line;
//	    	echo "</pre>";
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
    public function setLang(): array
    {
        $lang = [];
		// todo handle the case when one of those files is not presented (include fail)
        $config = parse_ini_file(self::$ROOT_DIR . "/config/lang.conf");
        // todo check session if the preference lang is stored use it otherwise take it from the config and set it to the session
	    if ($this->session->get('lang_main')) {
	    	/** already in session just get em */
		    array_push($lang, include self::$ROOT_DIR . '/translation/' . $this->session->get('lang_main') . '.lang.php');
		    array_push($lang, include self::$ROOT_DIR . '/translation/' . $this->session->get('lang_fb') . '.lang.php');
	    } else {
	    	/** not in session check database */
		    if ($this->preferences) {
		    	$language = languages::getLang($this->preferences->language)->language;
		    	Application::$APP->session->set('lang_main', $language);
			    array_push($lang, include self::$ROOT_DIR . '/translation/' . $language. '.lang.php');
			    if ($lang != $config['fallback_language']) {
				    Application::$APP->session->set('lang_fb', $config['fallback_language']);
				    array_push($lang, include self::$ROOT_DIR . '/translation/' . $config['fallback_language'] . '.lang.php');
			    } else {
				    Application::$APP->session->set('lang_fb', $config['main_language']);
				    array_push($lang, include self::$ROOT_DIR . '/translation/' . $config['main_language'] . '.lang.php');
			    }
		    } else {
		    	/** not in database take default setting from config file */
		        array_push($lang, include self::$ROOT_DIR . '/translation/' . $config['main_language'] . '.lang.php');
		        array_push($lang, include self::$ROOT_DIR . '/translation/' . $config['fallback_language'] . '.lang.php');
		    }
	    }

        return $lang;
    }
	
	/**
	 * @param string $name
	 * @return string
	 */
	public static function path(string $name, $var = null): string
    {
    	return self::$APP->router->path($name, $var);
    }
	
	public function login(DbModel $user, string $ref)
	{
		$this->user = $user;
		$primaryKey = $user->primaryKey();
		$primaryValue = $user->{$primaryKey};
		$this->session->set('user', $primaryValue);
		$this->response->redirect($ref);
	}
	
	public static function logout()
	{
		$token = Application::$APP->request->getBody() ?? '';
		Application::$APP->user = NULL;
		Application::$APP->session->remove('user');

		Application::$APP->response->redirect('/');
	}
}
