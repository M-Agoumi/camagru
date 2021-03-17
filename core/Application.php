<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   Application.php                                   :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/03/17 09:11:16 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/03/17 09:11:16 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */


require_once 'Router.php';
require_once 'Request.php';
require_once 'Response.php';
require_once '../controller/Controller.php';

/**
 * Class Application
 */
class Application
{
	public static string $ROOT_DIR;
	public static Application $APP;
	public ?Router $router = null;
	public ?Request $request = null;
	public ?Response $response = null;
	public ?Controller $controller = null;

	/**
	 * Application constructor.
	 * @param $rootPath string the root path of our application
	 */

	public function __construct(string $rootPath)
	{
		self::$ROOT_DIR = $rootPath;
		self::$APP = $this;
		$this->request = New Request();
		$this->response = New Response();
		$this->router = New Router($this->request, $this->response);
	}

	/**
	 * calling the resolver method to handle our request
	 */
	public function run()
	{
		echo $this->router->resolve();
	}

	/**
	 * @return Controller|null
	 */
	public function getController(): ?Controller
	{
		return $this->controller;
	}

	/**
	 * @param Controller|null $controller
	 */
	public function setController(?Controller $controller): void
	{
		$this->controller = $controller;
	}

}