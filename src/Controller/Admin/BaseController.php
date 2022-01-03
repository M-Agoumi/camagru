<?php


namespace Controller\Admin;


use controller\Controller;
use Middlewares\AdminMiddleware;
use Middlewares\AuthMiddleware;

class BaseController extends Controller
{
	public function __construct()
	{
		$this->registerMiddleware(New AuthMiddleware([]));
		$this->registerMiddleware(New AdminMiddleware([]));
	}
}
