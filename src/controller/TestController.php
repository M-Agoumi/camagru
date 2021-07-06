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

	public function mailTest()
	{
		var_dump($this->mailer('agoumihunter@gmail.com', 'testing', 'this is a huge fat test'));
	}
}
