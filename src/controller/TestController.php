<?php


namespace controller;

use Middlewares\DevMiddleware;
use models\User;

class TestController extends Controller
{
	public function __construct()
	{
		$this->registerMiddleware(New DevMiddleware([]));
	}

	public function linkVar($var = 'test')
	{
		return $var;
	}

	public function imageCanvas(User $user)
	{
		return $this->render('test', ['user' => $user]);
	}
}
