<?php

require_once "Request.php";

class Router
{
	public Request $request;
	protected array $routes = [];

	public function __construct(Request $request)
	{
		$this->request = $request;
	}
	/*
	 * @return the url of the current page
	 * @return type string
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
		$this->request()->getPath();
	}
}