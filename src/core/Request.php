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

namespace core;

/**
 * Class Request
 */
class Request
{
	/**
	 * @return string the current path of the application
	 */
	public function getPath(): string
	{
		$path = $_SERVER['REQUEST_URI'] ?? '/';
		$position = strpos($path, '?');
		if (substr($path, -1) === '/' && $path != '/')
			$path = substr_replace($path ,"", -1);

		if ($position === false)
			return $path;
		
		return substr($path, 0, $position);
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
	 * todo add more filters for more security
	 * @return array
	 */
	public function getBody(): array
	{
		$body = [];
		if ($this->Method() === 'get') {
			if (Application::getEnvValue('csrfVerification')) {
				if (isset($_GET['__csrf']) && !empty($_GET['__csrf'])) {
					if ($_GET['__csrf'] !== Application::$APP->session->getCsrf())
						die("wrong CSRF token please refresh the form page and retry again, if the problem didn't go please
					contact an admin");
				} else {
					die("Form submitted without CSRF token");
				}
			}
			foreach ($_GET as $key => $value) {
				$body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		}
		if ($this->Method() === 'post') {
			if (Application::getEnvValue('csrfVerification')) {
				if (isset($_POST['__csrf']) && !empty($_POST['__csrf'])) {
					if (!Application::$APP->session->checkCsrf($_POST['__csrf']))
						die("wrong CSRF token please refresh the form page and retry again, if the problem didn't go please
					contact an admin");
				} else {
					die("Form submitted without CSRF token");
				}
			}
			foreach ($_POST as $key => $value) {
				$body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		}
		return $body;
	}

	/**
	 * @return array|bool
	 */
	public function magicPath()
	{
		$x = $this->getRoutes();
		$path = $this->getMagicPath($this->getPath());
		$ret = null;
		foreach ($x as $key => $value) {
			// print_r($value);
			if (isset($value[$path])) {
				$ret = $value[$path];
				array_push($ret, $this->getPathVar());
				break;
			}
		}
		return $ret ?? false;
	}

	public function getRoutes(): array
	{
		$routes = Application::$APP->router->routes['magic'] ?? [];
		$newRoutes = [];
		foreach ($routes as $key => $value) {
			$key = preg_replace('~\{.*\}~', "", $key);
			$key = substr_replace($key, "", -1);
			$newRoutes[] = array($key => $value);

		}
		return $newRoutes;
	}

	private function getMagicPath(string $path)
	{
		$position = strrpos($path, '/');
		return substr($path, 0, $position);
	}

	private function getPathVar()
	{
		$path = $this->getPath();
		$position = strrpos($path, '/');
		return (substr($path, $position + 1));
	}
}