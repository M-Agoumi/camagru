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

use Simfa\Action\Controller;
use Simfa\Framework\Db\Database;
use Simfa\Framework\Db\DbModel;
use Exception;
use Simfa\Model\Languages;
use Simfa\Model\Preferences;

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
	public ?Preferences $preferences = null;
	public ?DbModel $user;
	public ?View $view;
	public ?Helper $helper = null;
	public ?Cookie $cookie = null;
	public ?Catcher $catcher = null;
	public array $MainLang = [];
	protected array $fallbackLang = [];
	public string $interface;

	/**
	 * Application constructor.
	 * @param $rootPath string the root path of our application
	 */

	public function __construct(string $rootPath, string $appInterface = 'web')
	{
		self::$ROOT_DIR = $rootPath;
		self::$APP = $this;
		self::$ENV = $this->getDotEnv();
		$this->catcher = New Catcher();
		$this->interface = $appInterface;
		$this->userCLass = self::getEnvValue('userClass') ?? 'models\User';
		$this->request = New Request();
		$this->response = New Response();
		$this->db = New Database($this->getDatabaseConfig());
		$this->session = New Session();
		$this->user = self::getUser();
		$this->router = New Router($this->request, $this->response, $appInterface);
		$this->view = New View();
		$this->helper = New Helper();
		$this->cookie = New Cookie();
		$this->preferences = $this->user ? Preferences::getPerf($this->user->getId()) : null;
		$lang = $this->setLang();
		$this->MainLang = $lang[0];
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
			$user->ip_address = Application::$APP->request->getUserIpAddress();
			$user->update();

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
	 * calling the resolver method to handle our request
	 */
	public function run()
	{
	    try {
	    	$output = $this->router->resolve();
	    	if (is_string($output))
                echo $output;
	    	elseif(is_bool($output))
			    echo render('messages.default', ['title' => 'empty page', 'value' => $output]);
	    	else
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
		return file_exists(self::$ROOT_DIR . "/config/" . $file . ".conf") ?
			parse_ini_file(self::$ROOT_DIR . "/config/" . $file . ".conf", true) : [];
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
		    array_push($lang, include self::$ROOT_DIR . '/translation/' . $this->session->get('lang_main') . '.lang.php');
		    array_push($lang, include self::$ROOT_DIR . '/translation/' . $this->session->get('lang_fb') . '.lang.php');
	    } else {
	    	/** not in session check database */
		    if ($this->preferences && $this->preferences->id) {
		    	$language = Languages::getLang($this->preferences->language)->language;
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
	 * @param null $var
	 * @return string
	 */
	public static function path(string $name, $var = null): string
    {
    	return self::$APP->router->path($name, $var);
    }

	/** save our logged user to the session
	 * @param DbModel $user
	 * @param string $ref
	 */
	public function login(DbModel $user, string $ref)
	{
		$this->user = $user;
		$primaryKey = 'get' . ucfirst($user->primaryKey());
		$primaryValue = $user->{$primaryKey}();
		$this->session->set('user', $primaryValue);
		$this->response->redirect($ref);
	}

	/**
	 * @param string $redirect to the last page the user was in
	 */
	public static function logout(string $redirect = '/')
	{
		Application::$APP->request->getBody();
		Application::$APP->session->set('user_tmp', Application::$APP->user ? Application::$APP->user->getId() : 0);
		Application::$APP->user = NULL;
		Application::$APP->session->remove('user');
		Application::$APP->cookie->unsetCookie('user_tk');

		Application::$APP->response->redirect($redirect);
	}
}
