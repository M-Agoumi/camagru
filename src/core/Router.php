<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   Router.php                                        :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/03/17 09:12:14 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/03/17 09:12:14 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

namespace core;

use controller\Controller;
use core\Exception\NotFoundException;
use ReflectionClass;


class Router
{
	public Request $request;
	public ?Response $response = null;
	public array $routes = [];
	protected array $paths = [];
	protected string $tmp;

	/**
	 * Router constructor.
	 * save an instance of request and response  from our application
	 * so we can call them with $this whenever we need them
	 * @param Request $request
	 * @param Response $response
	 */
	public function __construct(Request $request, Response $response)
	{
		$this->request = $request;
		$this->response = $response;
	}

	/**
	 * Store a callback in an array with its associated
	 * path (get method)
	 *
	 * @param $path
	 * @param $callback
	 * does not return anything
	 * @return Router
	 */
	public static function get($path, $callback): Router
	{
		return self::setRoute('get', $path, $callback);
	}

	private static function setRoute(string $method, $path, $callback)
	{
		$router = Application::$APP->router;

		$path = strtolower($path);
		if (!isset($router->routes[$method][$path]))
			$router->routes[$method][$path] = $callback;
		else
			die("route $path is already used please update it");
		$router->tmp = $path;

		return $router;
	}

	/**
	 * Store a callback in an array with its associated
	 * path (post method)
	 *
	 * @param $path
	 * @param $callback
	 *
	 * does not return anything
	 * @return Router
	 */
	public static function post($path, $callback): Router
	{
		return self::setRoute('post', $path, $callback);
	}

	public static function magic($path, $callback): Router
	{
		return self::setRoute('magic', $path, $callback);
	}

	public function name(string $name)
	{
		if (!isset($this->paths[$name]))
			$this->paths[$name] = $this->tmp;
		else
			die("the path name [$name] is already used");
	}

	/**
	 * @param string $name
	 * @param $var
	 * @return string
	 */
	public function path(string $name, $var = null): string
	{
		/** check if it's a magic link */
		if (isset($this->paths[$name])) {
			if ($var) {
				$path = $this->paths[$name];
				return preg_replace('~\{.*\}~', $var, $path);
			} else
				return $this->paths[$name];
		}


		/** todo throw and exception  */
		die("there is no path with the name $name");
	}

	/**
	 * the heart of our routes
	 * check if there is a callback for the current path
	 * and execute it depends on its type
	 * otherwise return 404 error
	 * @return false|mixed|string|string[]
	 * @throws NotFoundException
	 */
	public function resolve()
	{
		$callback = $this->getCallbackOrFail();

		if (is_string($callback))
			return $this->renderView($callback);

		if (is_array($callback))
			return $this->execArrayCallback($callback);

		if (is_callable($callback))
			return call_user_func($callback, $this->request);

		return "Method [$callback[1]] is not found in [" . get_class($callback[0]) . ']';
	}

	public function getCallbackOrFail()
	{
		$this->getRoutes();
		$path = strtolower($this->request->getPath());
		$method = $this->request->Method();
		$callback = $this->routes[$method][$path] ?? false;

		if ($callback === false)
			$callback = $this->request->magicPath();

		if ($callback === false)
			throw new NotFoundException();

		return $callback;
	}

	protected function execArrayCallback($callback)
	{
		/** @var Controller $controller */
		$controller = new $callback[0]();
		Application::$APP->controller = $controller;
		$controller->action = $callback[1];

		foreach ($controller->getMiddlewares() as $middleware) {
			$middleware->execute();
		}

		$callback[0] = $controller;

		$params = $this->injectDependencies($callback);
		unset($callback[2], $callback[3]);

		return call_user_func_array($callback, $params);
//		if (sizeof($callback) === 3)
//			return $controller->{$callback[1]}($callback[2], $this->request);
	}

	/**
	 *
	 * @throws \ReflectionException
	 */
	protected function injectDependencies($callback): array
	{
		$params = [];

		$reflector = new ReflectionClass($callback[0]);
		foreach ($reflector->getMethod($callback[1])->getParameters() as $param) {
			$modelName =  $param->name;

			$modelType = $param->getClass()->name ?? NULL;
			array_push($params, $this->injectClassOrModule($modelType, $modelName, $callback));
		}

		return $params;
	}

	/**
	 * @param $type
	 * @param $name
	 * @param $callback
	 * @return mixed
	 * @throws NotFoundException
	 */
	protected function injectClassOrModule($type, $name, $callback)
	{
		$param = null;

		if (Application::isAppProperty($name) && $name != 'user') {
			$param = Application::$APP->$name;
		} else {
			if (class_exists($type)) {
				$param = new $type();
				$primaryKey = $callback[2] ?? '';

				if ($name == $primaryKey)
					$primaryKey = $param->primaryKey();

				if (isset($callback[3]))
					$param = $param::findOne([$primaryKey => $callback[3]]);

				if (!$param)
					throw new NotFoundException();
			} else {
				if (isset($callback[3])) {
					$param = $callback[3];
				} else {
					die('class ' . $type . ' not found while trying to inject it');
				}
			}
		}

		return $param;
	}

	protected function renderView(string $callback)
	{
		return Application::$APP->view->renderView($callback);
	}

	private function getRoutes()
	{
		include Application::$ROOT_DIR . "/routes/web.php";
	}
}
