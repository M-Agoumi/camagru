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


require_once "Request.php";
require_once "Response.php";

class Router
{
	public Request $request;
	public ?Response $response = null;
	protected array $routes = [];

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
	 */
	public function get($path, $callback)
	{
		if (!isset($this->routes['get'][$path]))
			$this->routes['get'][$path] = $callback;
		else
			die("route $path is already used please update it");
	}

	/**
	 * Store a callback in an array with its associated
	 * path (post method)
	 *
	 * @param $path
	 * @param $callback
	 *
	 * does not return anything
	 */
	public function post($path, $callback)
	{
		if (!isset($this->routes['post'][$path]))
			$this->routes['post'][$path] = $callback;
		else
			die("route $path is already used please update it");
	}

	/**
	 * the heart of our routes
	 * check if there is a callback for the current path
	 * and execute it depends on its type
	 * otherwise return 404 error
	 * @return false|mixed|string|string[]
	 */
	public function resolve()
	{
		$path = $this->request->getPath();
		$method = $this->request->Method();
		$callback = $this->routes[$method][$path] ?? false;
		if ($callback === false)
		{
			$this->response->setStatusCode(404);
			return $this->renderContent("<title>404 Not Found</title><h1>404</h1><h2>Ops.. Page Not Found</h2>");
		}
		if (is_string($callback))
			return $this->renderView($callback);
		if (is_array($callback)) {
			Application::$APP->controller = new $callback[0]();
			$callback[0] = Application::$APP->controller;
		}

		if (is_callable($callback))
			return call_user_func($callback, $this->request);

		return "Method [$callback[1]] is not found in [" . get_class($callback[0]) . ']';
	}

	/**
	 * mix the template with the asked $view
	 *
	 * @param $view string name of the view we want to compile with the template
	 * @param array $params
	 * @return false|string|string[]
	 */
	public function renderView(string $view, array $params = [])
	{

		$layout = $this->layoutContent();
		$view = $this->renderOnlyView($view, $params);
		return str_replace('{{ body }}', $view, $layout);
	}

	/**
	 * @param string $content content to be rendered in the template
	 * @return false|string|string[]
	 */
	public function renderContent(string $content)
	{
		$layout = $this->layoutContent();
		return str_replace('{{ body }}', $content, $layout);
	}

	/**
	 * @return false|string the template content
	 */

	public function layoutContent()
	{
		$layout = Application::$APP->controller->layout ?? 'main';
		ob_start();
		include_once Application::$ROOT_DIR . "/views/layout/$layout.layout.php";
		return ob_get_clean();
	}

	/**
	 * @param $view string the wanted view
	 * @param array $params
	 * @return string|null the view content
	 */
	protected function renderOnlyView(string $view, array $params): ?string
	{
		foreach ($params as $key => $param) {
			$$key = $param;
		}
		ob_start();
		include_once Application::$ROOT_DIR . "/views/$view.blade.php";
		return ob_get_clean();
	}

    /**
     * get the value of the key from the language used
     * @param string $key
     * @return string
     */
    public function lang(string $key): string
    {
        return Application::$APP->lang($key);
    }
}