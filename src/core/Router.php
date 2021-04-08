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
	public function get($path, $callback): Router
	{
		if (!isset($this->routes['get'][$path]))
			$this->routes['get'][$path] = $callback;
		else
			die("route $path is already used please update it");
		$this->tmp = $path;
		return $this;
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
	public function post($path, $callback): Router
	{
		if (!isset($this->routes['post'][$path]))
			$this->routes['post'][$path] = $callback;
		else
			die("route $path is already used please update it");
		$this->tmp = $path;
		return $this;
	}

	public function magic($path, $callback): Router
	{
		if (!isset($this->routes['magic'][$path]))
			$this->routes['magic'][$path] = $callback;
		else
			die("route $path is already used please update it");
		$this->tmp = $path;
		return $this;
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
	 * @return string
	 */
	public function path(string $name): string
	{
		if ($this->paths[$name])
			return $this->paths[$name];
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
		$path = $this->request->getPath();
		$method = $this->request->Method();
		$callback = $this->routes[$method][$path] ?? false;

		if ($callback === false)
			$callback = $this->request->magicPath();

		if ($callback === false)
			throw new NotFoundException();

		if (is_string($callback))
			return $this->renderView($callback);

		if (is_array($callback)) {
			/** @var Controller $controller */
			$controller = new $callback[0]();
			Application::$APP->controller = $controller;
			$controller->action = $callback[1];
			foreach ($controller->getMiddlewares() as $middleware) {
				$middleware->execute();
			}

			$callback[0] = $controller;

			if (sizeof($callback) === 3)
				return $controller->{$callback[1]}($callback[2]);
		}

		if (is_callable($callback))
			return call_user_func($callback, $this->request);

		return "Method [$callback[1]] is not found in [" . get_class($callback[0]) . ']';
	}

	protected function renderView(string $callback)
	{
		return Application::$APP->view->renderView($callback);
	}
}