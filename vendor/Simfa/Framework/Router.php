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

use Simfa\Action\Controller;
use Simfa\Framework\Db\DbModel;
use Simfa\Framework\Exception\ExpiredException;
use Simfa\Framework\Exception\NotFoundException;
use ReflectionClass;


class Router
{
	public Request $request;
	public ?Response $response = null;
	public array $routes = [];
	protected array $paths = [];
	protected string $tmp;
	private string $interface;
	private $callback;

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
		$router = Application::$APP->router;

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
	 */
	public static function redirect(string $from, string $to, string $method = null)
	{
		if ($method == 'get' || $method == null)
			self::registerRoute('get', $from, function () use($to){redirect($to);});
		if ($method == 'post' || $method == null)
			self::registerRoute('post', $from, function () use($to){redirect($to);});
	}

	/** register a name for the current path
	 * @param string $name
	 */
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
	 * @throws \Exception
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

		throw new \Exception("there is no path with the name $name", '0');
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
		$this->callback = $callback;

		if (is_string($callback))
			return render($callback);
		
		if (is_array($callback))
			return $this->execArrayCallback($callback);

		if (is_callable($callback))
			return call_user_func($callback, $this->request);

		return "Method [$callback[1]] is not found in [" . get_class($callback[0]) . ']';
	}

	/**
	 * @throws NotFoundException
	 */
	public function getCallbackOrFail()
	{
		$this->getRoutes();

		/** check if there is any register routes in the app otherwise show default page */
		if (empty($this->routes))
			die (render('default.firstRun'));

		$path = $this->request->getPath();
		$method = $this->request->Method();
		$callback = $this->routes[$method][$path] ?? false;

		$newPath = str_replace(Application::getEnvValue('url'), '', $path);
		$newPath = !empty($newPath) ? $newPath : '/';

		$callback = $callback ?: $this->routes[$method][$newPath] ?? false;
		$callback = $callback ?: $this->request->magicPath();

		if ($callback === false)
			throw new NotFoundException();

		return $callback;
	}

	/**
	 * @throws \ReflectionException
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
			die('method [' . $callback[1] . '] not found in class [' . get_class($callback[0]) . ']');

		$params = $this->injectDependencies($callback);
		unset($callback[2], $callback[3]);

		return call_user_func_array($callback, $params);
	}

	/**
	 * @param $callback
	 * @return array
	 * @throws \ReflectionException
	 */
	protected function injectDependencies($callback): array
	{
		$params = [];

		$reflector = new ReflectionClass($callback[0]);

		foreach ($reflector->getMethod($callback[1])->getParameters() as $param) {
			$modelName = $param->name;
			$modelType = $param->getClass()->name ?? NULL;

			if ($modelType)
				array_push($params, $this->injectClassOrModule($modelType, $modelName));
			else
				array_push($params, $callback[3] ?? NULL);
		}

		return $params;
	}

	/**
	 * @param $type
	 * @param $name
	 * @return mixed
	 */
	protected function injectClassOrModule($type, $name)
	{
		if (Application::isAppProperty($name) && $name != 'user') /** make dynamic instead of user */
			return Application::$APP->$name;
		else
			return $this->injectModule($type);
	}

	/**
	 * load routes from the corresponding interface to the running app
	 */
	private function getRoutes()
	{
		include Application::$ROOT_DIR . "/routes/" . $this->interface . ".php";
	}

	/** inject module
	 * @param $type
	 * @return object|void
	 * @throws \ReflectionException
	 */
	private function injectModule($type)
	{
		if (class_exists($type))
			return $this->createModuleInstance($type);

		die('class ' . $type . ' not found while trying to inject it');
	}

	/**
	 * @throws \ReflectionException
	 * @throws ExpiredException
	 * @throws NotFoundException
	 */
	private function createModuleInstance(string $type): object
	{
		/** @var  $arguments array of constructor arguments */
		$arguments = $this->getClassConstructorArguments($type);
		$reflector = new ReflectionClass($type);
		$name = $reflector->getShortName();

		$instance = $reflector->newInstanceArgs($arguments);

		$relations = [];

		if (method_exists($instance, 'relationships'))
			$relations = $instance->relationships();

		if ($instance instanceof DbModel) {
			$primaryKey = $this->callback[2] ?? '';

			if ($name == $primaryKey)
				$primaryKey = $instance->primaryKey();

			if ($primaryKey) {
				if (isset($this->callback[3])) {
					$instance = $instance::findOne([$primaryKey => $this->callback[3]], $relations);

					if (!$instance->getId())
						if (strpos($this->callback[2], 'token') !== false)
							throw new ExpiredException('Invalid token');
						else
							throw new NotFoundException();
				}
			}
		}

		return $instance;
	}

	/**
	 * @throws \ReflectionException
	 */
	private function getClassConstructorArguments(string $type): ?array
	{
		$rf = new ReflectionClass($type);

		$constructorRef = $rf->getConstructor();
		$constructorArguments = $constructorRef ? $constructorRef->getParameters() : [];
		if (!count($constructorArguments))
			return [];

		$arguments = [];

		foreach ($constructorArguments as $argument)
		{
			$type = $argument->getType();
			$name = $argument->getName();
			$arguments[] = $this->injectClassOrModule($type, $name);
		}

		return $arguments;
	}
}

