<?php


namespace controller;


use core\Application;
use core\Exception\NotFoundException;
use models\User;

class UserController extends Controller
{
	public function index($username)
	{
		$user = new User();

		$user = $user->getOneBy('username', $username);
		if (!$user)
			throw new NotFoundException();
		if (Application::$APP->user) {
			if ($user->username === Application::$APP->user->username)
				return $this->render('pages/myProfile', ['user' => $user], ['title' => Application::$APP->user->name . " - Profile"]);
		}
		return $this->render('pages/profile', ['user' => $user], ['title' => $user->name . " - Profile"]);
	}

	public function myProfile()
	{
		if (Application::$APP->user)
			Application::$APP->response->redirect('/user/' . Application::$APP->user->username);
		else
			Application::$APP->response->redirect(Application::path('auth.login'));
	}
}