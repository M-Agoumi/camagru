<?php

require_once 'Router.php';
require_once 'Request.php';

class Application
{
	public ?Router $router = null;
	public ?Request $request = null;


	public function __construct()
	{
		$this->request = New Request();
		$this->router = New Router($this->request);
	}

	public function run()
	{
//		var_dump($this->route);
//		$url = $this->route->getUrl();
//		echo $url;
		$this->router->resolve();
	}

}