<?php

require_once "Request.php";
require_once "Response.php";

class Router
{
	public Request $request;
	public ?Response $response = null;
	protected array $routes = [];

	public function __construct(Request $request, Response $response)
	{
		$this->request = $request;
		$this->response = $response;
	}
	/*
	 * @return type string the url of the current page
	 */
	public function getUrl(): string
	{
		return $_SERVER['PATH_INFO'] ?? '/';
	}

	public function get($path, $callback)
	{
		if (!isset($this->routes['get'][$path]))
			$this->routes['get'][$path] = $callback;
		else
			die("route $path is already used please update it");
	}

	public function post($path, $callback)
	{
		if (!isset($this->routes['post'][$path]))
			$this->routes['post'][$path] = $callback;
		else
			die("route $path is already used please update it");
	}

	public function resolve()
	{
//		echo $this->getUrl();
		$path = $this->request->getPath();
		$method = $this->request->getMethod();
		$callback = $this->routes[$method][$path] ?? false;
		if ($callback === false)
		{
			$this->response->setStatusCode(404);
			return "Ops.. Page Not Found <h1>404</h1>";
		}
		if (is_string($callback))
			return $this->renderView($callback);
		return call_user_func($callback);
	}

	public function renderView($view)
	{
		$layout = $this->layoutContent();
		$view = $this->renderOnlyView($view);
		return str_replace('{{ body }}', $view, $layout);
	}

	public function layoutContent()
	{
		ob_start();
		include_once Application::$ROOT_DIR . "/views/layout/main.layout.php";
		return ob_get_clean();
	}

	protected function renderOnlyView($view)
	{
		ob_start();
		include_once Application::$ROOT_DIR . "/views/$view.blade.php";
		return ob_get_clean();
	}
}