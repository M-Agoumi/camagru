<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        ::::::::            */
/*   Request.php                                        :+:    :+:            */
/*                                                     +:+                    */
/*   By: magoumi <magoumi@student.1337.ma>            +#+                     */
/*                                                   +#+                      */
/*   Created: 2021/03/16 17:42:36 by null          #+#    #+#                 */
/*   Updated: 2021/03/16 17:42:36 by null          ########   odam.nl         */
/*                                                                            */
/* ************************************************************************** */

namespace Simfa\Framework;

use Exception;

/**
 * Class Request
 */
class Request
{

	public function __construct()
	{
		/** get whitelisted cors */
		$this->cors();
	}

	/**
	 * gets the current page path(url)
	 * @param bool $firstSlash doesn't return first slash in case of true localhost/bar returns 'bar' instead of '/bar'
	 * @return string the current path of the application
	 */
	public function getPath(bool $firstSlash = false): string
	{
		$path = $_SERVER['REQUEST_URI'] ?? '';

		if ($path == '/')
			return '';
		$position = strpos($path, '?');

		if (str_ends_with($path, '/'))
			$path = substr_replace($path ,"", -1);

		if ($firstSlash)
			if ($path && $path[0] === '/')
				$path = substr($path, 1);

		if ($position === false)
			return $path;
		
		return substr($path, 0, $position);
	}

	/** get server port [default 80 but in case of dev env, the default is 8000]
	 * @return ?string
	 */
	public function port(): ?string
	{
		return $_SERVER['SERVER_PORT'] == '80' ? false : (string)$_SERVER['SERVER_PORT'];
	}

	/**
	 * @return string the method of our request [get|post] in lowercase
	 */
	public function Method(): string
	{
		return strtolower($_SERVER['REQUEST_METHOD']);
	}

	/**
	 * @return bool
	 */
	public function isGet(): bool
	{
		return $this->Method() === 'get';
	}

	/**
	 * @return bool
	 */
	public function isPost(): bool
	{
		return $this->Method() === 'post';
	}

	/**
	 * handle the data coming from a form
	 * and check for csrf token if its validation is enabled in env
	 * @return array
	 * @throws Exception
	 */
	public function getBody(): array
	{
		$csrf = $_SERVER['HTTP_CSRF'] ?? $_POST['__csrf'] ?? $_GET['__csrf'] ?? false;
		$body = [];

		if (Application::getEnvValue('CSRF_VERIFICATION')) {
			if ($csrf) {
				if (!Application::$APP->session->checkCsrf($csrf))
				throw new Exception("wrong CSRF token please refresh the form page and retry again, if the problem didn't go 
						please contact an admin", '401');
			} else {
				throw new Exception("Form submitted without CSRF token", '401');
			}
		}

		if ($this->Method() === 'get') {
			foreach ($_GET as $key => $value) {
				$body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		} else {
			foreach ($_POST as $key => $value) {
				$body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		}

		return $body;
	}

	/**
	 * @return array|bool
	 */
	public function magicPath(): bool|array
	{
		$routes = $this->getRoutes();
		$path = $this->getMagicPath($this->getPath());
		$return = null;

		foreach ($routes as $key => $value) {
			if (isset($value[$path])) {
				$return = $value[$path];

				if (is_array($return))
					array_push($return, $this->getPathVar());
				break;
			}
		}

		return $return ?? false;
	}

	/**
	 * @return array
	 */
	public function getRoutes(): array
	{
		$routes = Application::$APP->router->routes['magic'] ?? [];
		$newRoutes = [];

		foreach ($routes as $key => $value) {
			$url_variable = $this->getStringBetween($key);
			$key = str_replace('/{'.$url_variable . '}', "", $key);
			if (is_array($value))
				$value[] = $url_variable;
			$newRoutes[] = array($key => $value);
		}

		return $newRoutes;
	}

	/**
	 * @param $string
	 * @return string
	 */
	private function getStringBetween($string): string
	{
		$string = ' ' . $string;
		$ini = strpos($string, '{');
		if ($ini == 0) return '';
		$ini += strlen('{');
		$len = strpos($string, '}', $ini) - $ini;

		return substr($string, $ini, $len);
	}

	/**
	 * @param string $path
	 * @return string
	 */
	private function getMagicPath(string $path): string
	{
		$position = strrpos($path, '/');

		return substr($path, 0, $position);
	}

	/**
	 * @return string
	 */
	private function getPathVar(): string
	{
		$path = $this->getPath();
		$position = strrpos($path, '/');

		return (substr($path, $position + 1));
	}

	/**
	 * @return mixed|string
	 */
	public function getUserIpAddress(): mixed
	{
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			//ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			//ip pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else
			$ip = $_SERVER['REMOTE_ADDR'] ?? '';

		return $ip;
	}

	private function cors()
	{
		$interface = Application::$APP->interface;
		$config = Application::getConfig('cors');

		$config = implode(', ', $config[$interface] ?? []);
		header('Access-Control-Allow-Origin: ' . $config);
	}

	/** return last key of the url
	 * @return string
	 */
	public static function getSimpleUrl(): string
	{
		return (basename($_SERVER['REQUEST_URI']));
	}
}
