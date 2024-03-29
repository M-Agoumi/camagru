<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   Application.php                                   :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/03/17 09:11:16 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/10/20 02:04:52 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

namespace Simfa\Framework;

use Exception;
use Simfa\Model\Language;
use Simfa\Model\Preference;
use Simfa\Action\Controller;
use Simfa\Framework\Db\DbModel;
use Simfa\Framework\Db\Database;

/**
 * Class Application don't forget to include your user class
 */
class Application
{
	public static string        $ROOT_DIR;
	public static Application   $APP;
	public static array         $ENV;
	public string               $userCLass;
	public Database             $db;
	public ?Router              $router = null;
	public ?Request             $request = null;
	public ?Response            $response = null;
	public ?controller          $controller = null;
	public ?Session             $session = null;
	public ?Preference          $preferences = null;
	public ?DbModel             $user;
	public ?View                $view;
	public ?Helper              $helper = null;
	public ?Cookie              $cookie = null;
	public ?Catcher             $catcher = null;
	public ?Injector            $injector = null;
	public array                $MainLang = [];
	protected array             $fallbackLang = [];
	public string               $interface;
	private ?array              $appConfig = null;

	/**
	 * Application constructor.
	 * @param $rootPath string the root path of our application
	 */

	public function __construct(string $rootPath, string $appInterface = 'web')
	{
		self::$ROOT_DIR     = $rootPath;
		self::$APP          = $this;
		self::$ENV          = $this->getDotEnv();
		$this->interface    = $appInterface;
		$this->injector     = new Injector();
		$this->catcher      = new Catcher();
		$this->userCLass    = self::getEnvValue('USER_CLASS') ?? 'Model\User';
		$this->request      = New Request();
		$this->response     = New Response();
		$this->view         = New View();
		$this->db           = New Database($this->getDatabaseConfig());
		$this->session      = New Session();
		$this->user         = self::getUser();
		$this->router       = New Router($this->request, $this->response, $appInterface);
		$this->helper       = Helper::initHelper();
		$this->cookie       = new Cookie();
		$this->preferences  = $this->user ? Preference::getPerf($this->user->getId()) : null;
		$lang               = $this->setLang();
		$this->MainLang     = $lang[0];
		$this->fallbackLang = $lang[1];
	}

	/** get logged user info and update his ip address
	 * @return null
	 */
	private static function getUser()
	{
		/** @var  $this->userClass User */
		$primaryValue = self::$APP->session->get('user');
		if ($primaryValue) {
			$primaryKey = self::$APP->userCLass::primaryKey();
			$user = self::$APP->userCLass::findOne([$primaryKey =>  $primaryValue]);
			if ($user->getId())
				return $user;
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

	/** is the instance we're looking for exists in the App?
	 * @param $instance
	 * @return bool
	 */
	public static function isAppProperty($instance): bool
	{
		return (property_exists(Application::class, $instance));
	}

	/**
	 * @param string $string
	 * @param string $key
	 * @return mixed
	 */
	public static function getAppConfig(string $string, string $key = ''): mixed
	{
		if (!self::$APP->appConfig)
			self::$APP->appConfig = include(Application::$ROOT_DIR . '/config/config.php');

		if ($key)
			return self::$APP->appConfig[$string][$key] ?? null;
		return self::$APP->appConfig[$string] ?? null;
	}

	/**
	 * calling the resolver method to handle our request
	 */
	public function run(): void
	{
	    try {
	    	$output = $this->router->resolve();
	    	if (is_string($output))
                echo $output;
	    	elseif(is_bool($output))
			    echo render('messages.default', ['title' => 'empty page', 'value' => $output]);
			elseif (!is_null($output))
	    		var_dump($output);
        } catch (Exception $e) {
	    	$this->catcher->catch($e);
        }
	}

    /**
     * get the setting in .env file
     * @return array
     */
	public function getDotEnv() : array
	{
		return file_exists(self::$ROOT_DIR . "/.env") ?
				parse_ini_file(self::$ROOT_DIR . "/.env") : [];
	}

    /**
     * @param $attr
     * @return string|null env value if it exists
     */
    public static function getEnvValue($attr): ?string
    {
		return self::$ENV[$attr] ?? null;
	}

    /**
     * read database config file or return null
     * @return array|false
     */
    private function getDatabaseConfig()
	{
		return self::getConfig('db');
	}

	/**
	 * get configs from config file
	 */
	public static function getConfig(string $file)
	{
		if (file_exists(self::$ROOT_DIR . "/config/" . $file . ".conf"))
			return parse_ini_file(self::$ROOT_DIR . "/config/" . $file . ".conf", true);
		if (file_exists(self::$ROOT_DIR . "/config/" . $file . ".php"))
			return include(Application::$ROOT_DIR . '/config/'. $file . '.php');
		return  [];
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

	    if ($this->session->get('lang_main') && $this->session->get('lang_fb')) {
	    	/** already in session just get em */
		    $lang[] = include self::$ROOT_DIR . '/translation/' . $this->session->get('lang_main') . '.lang.php';
		    $lang[] = include self::$ROOT_DIR . '/translation/' . $this->session->get('lang_fb') . '.lang.php';
	    } else {
	    	/** not in session check database */
		    if ($this->preferences && $this->preferences->entityID) {
		    	$language = Language::getLang($this->preferences->language)->language;
		    	Application::$APP->session->set('lang_main', $language);
			    $lang[] = include self::$ROOT_DIR . '/translation/' . $language . '.lang.php';
			    if ($lang != $config['fallback_language']) {
				    Application::$APP->session->set('lang_fb', $config['fallback_language']);
				    $lang[] = include self::$ROOT_DIR . '/translation/' . $config['fallback_language'] . '.lang.php';
			    } else {
				    Application::$APP->session->set('lang_fb', $config['main_language']);
				    $lang[] = include self::$ROOT_DIR . '/translation/' . $config['main_language'] . '.lang.php';
			    }
		    } else {
		    	/** not in database take default setting from config file */
		        $lang[] = include self::$ROOT_DIR . '/translation/' . $config['main_language'] . '.lang.php';
		        $lang[] = include self::$ROOT_DIR . '/translation/' . $config['fallback_language'] . '.lang.php';
		    }
	    }

        return $lang;
    }

	/**
	 * @param string $name
	 * @param null $var
	 * @return string
	 */
	public static function path(string $name, $var = null): string
    {
	    try {
	    	return self::$APP->router->path($name, $var);
	    } catch (Exception $e) {
			Application::$APP->catcher->catch($e);
	    }
		return '';
    }

	/** save our logged user to the session
	 * @param DbModel $user
	 * @param string $redirect
	 */
	public function login(DbModel $user, string $redirect): void
	{
		$this->user = $user;
		$primaryKey = 'get' . ucfirst($user->primaryKey());
		$primaryValue = $user->{$primaryKey}();
		$this->session->set('user', $primaryValue);
		if (str_starts_with($redirect, '/'))
			$this->response->redirect(self::getEnvValue('URL') . $redirect);
		$this->response->redirect(self::getEnvValue('URL') . '/' . $redirect);
	}

	/**
	 * @param string $redirect to the last page the user was in
	 * @throws Exception
	 */
	public static function logout(string $redirect = '/')
	{
		Application::$APP->request->getBody();
		Application::$APP->session->set('user_tmp', Application::$APP->user ? Application::$APP->user->getId() : 0);
		Application::$APP->user = NULL;
		Application::$APP->session->remove('user');
		Application::$APP->cookie->unsetCookie('user_tk');

		Application::$APP->response->redirect(self::getEnvValue('URL') . $redirect);
	}
}
