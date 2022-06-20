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

namespace Simfa\Framework;

use Exception;
use ReflectionException;
use Simfa\Action\Controller;
use Simfa\Framework\Exception\NotFoundException;


class Router
{
	public Request $request;
	public ?Response $response = null;
	public array $routes = [];
	protected array $paths = [];
	protected string $tmp;
	private string $interface;

	/**
	 * Router constructor.
	 * save an instance of request and response  from our application, so we can call them with $this whenever we need
	 * them
	 * @param Request $request
	 * @param Response $response
	 * @param string $interface
	 */
	public function __construct(Request $request, Response $response, string $interface = 'web')
	{
		$this->request = $request;
		$this->response = $response;
		$this->interface = $interface;
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
		return self::registerRoute('get', $path, $callback);
	}

	/**
	 * @param string $method
	 * @param $path
	 * @param $callback
	 * @return Router|null
	 */
	private static function registerRoute(string $method, $path, $callback): ?Router
	{
		try {
			$router = Application::$APP->router;

			if (!isset($router->routes[$method][$path]))
				$router->routes[$method][$path] = $callback;
			else
				throw new Exception("route $path is already used please update it");
			$router->tmp = $path;
		} catch (Exception $e) {
			Application::$APP->catcher->catch($e);
		}

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
		return self::registerRoute('post', $path, $callback);
	}

	/** register magic path (path with variable)
	 * @param $path
	 * @param $callback
	 * @return Router
	 */

	public static function magic($path, $callback): Router
	{
		return self::registerRoute('magic', $path, $callback);
	}

	/** save route as a get and post request
	 * @param string $path
	 * @param $callback
	 * @return Router|null
	 */
	public static function request(string $path, $callback): ?Router
	{
		self::registerRoute('post', $path, $callback);

		return self::registerRoute('get', $path, $callback);
	}

	/** redirect from a path to a path, (ex: a link has been updated? set the old one to redirect to the new
	 * one so none is lost in 404)
	 * @param string $from
	 * @param string $to
	 * @param string|null $method
	 * @throws Exception
	 */
	public static function redirect(string $from, string $to, string $method = null)
	{
		if ($method == 'get' || $method == null)
			self::registerRoute('get', $from, function () use($to){redirect($to);});
		if ($method == 'post' || $method == null)
			self::registerRoute('post', $from, function () use($to){redirect($to);});
	}

	/**
	 * register a name for the current path
	 * @param string $name
	 */
	public function name(string $name)
	{
		try {
			if (!isset($this->paths[$name]))
				$this->paths[$name] = $this->tmp;
			else
				throw new Exception("the path name [$name] is already used");
		}catch (Exception $e) {
			Application::$APP->catcher->catch($e);
		}
	}

	/**
	 * @param string $name
	 * @param $var
	 * @return string
	 */
	public function path(string $name, $var = null): string
	{
		try {
			/** check if it's a magic link */
			if (isset($this->paths[$name])) {
				if ($var) {
					$path = $this->paths[$name];
					return preg_replace('~{.*}~', $var, $path);
				} else
					return $this->paths[$name];
			}

			throw new Exception("there is no path with the name $name", '0');
		}catch (Exception $e){
			Application::$APP->catcher->catch($e);
		}
	}

	/**
	 * the heart of our routes
	 * check if there is a callback for the current path
	 * and execute it depends on its type
	 * otherwise return 404 error
	 * @return false|mixed|string|string[]
	 * @throws NotFoundException|ReflectionException
	 */
	public function resolve()
	{
		$callback = $this->getCallbackOrFail();
		$this->callback = $callback;

		if (is_string($callback)){
			if (strlen($callback) > 612) // too long? that's a view to show otherwise is a file need to be showed
				return $callback;
			return render($callback);
		}

		if (is_array($callback))
			return $this->execArrayCallback($callback);

		if (is_callable($callback))
			return call_user_func($callback, $this->request);

		return "Method [$callback[1]] is not found in [" . get_class($callback[0]) . ']';
	}

	/**
	 * @throws NotFoundException
	 * @throws Exception
	 */
	public function getCallbackOrFail()
	{
		$this->getRoutes();

		/** check if there is any register routes in the app otherwise show default page */
		if (empty($this->routes))
			return (Application::$APP->view->renderViewSystem('defaults.firstRun'));

		$path = $this->request->getPath();
		$method = $this->request->Method();
		$callback = $this->routes[$method][$path] ?? false;

		$newPath = str_replace(Application::getEnvValue('URL'), '', $path);
		$newPath = !empty($newPath) ? $newPath : '/';

		$callback = $callback ?: $this->routes[$method][$newPath] ?? false;
		$callback = $callback ?: $this->request->magicPath();

		if ($callback === false)
			throw new NotFoundException();

		return $callback;
	}

	/**
	 * @throws ReflectionException
	 * @throws Exception
	 */
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

		if (!method_exists($callback[0], $callback[1]))
			throw new Exception('method [' . $callback[1] . '] not found in class [' . get_class($callback[0]) . ']');

		$params = Application::$APP->injector->getDependencies($callback[0], $callback[1],
			$callback[2] ?? null, $callback[3] ?? null);
		unset($callback[2], $callback[3]);

		return call_user_func_array($callback, $params);
	}

	/**
	 * load routes from the corresponding interface to the running app
	 */
	private function getRoutes()
	{
		include Application::$ROOT_DIR . "/routes/" . $this->interface . ".php";
	}
}

