<?php

require_once 'Router.php';
require_once 'Request.php';
require_once 'Response.php';	

class Application
{
	public static string $ROOT_DIR;
	public static Application $APP;
	public ?Router $router = null;
	public ?Request $request = null;
	public ?Response $response = null;


	public function __construct($rootPath)
	{
		self::$ROOT_DIR = $rootPath;
		self::$APP = $this;
		$this->request = New Request();
		$this->response = New Response();
		$this->router = New Router($this->request, $this->response);
	}

	public function run()
	{
		echo $this->router->resolve();
	}

}