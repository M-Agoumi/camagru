<?php


namespace Controller\Admin;

use Middlewares\AdminMiddleware;
use Middlewares\AuthMiddleware;
use Simfa\Action\Controller;

class BaseController extends Controller
{
	public function __construct()
	{
		$this->registerMiddleware(New AuthMiddleware([]));
		$this->registerMiddleware(New AdminMiddleware([]));
	}
}
