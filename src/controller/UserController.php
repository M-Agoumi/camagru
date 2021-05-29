<?php


namespace controller;


use core\Application;
use core\Exception\NotFoundException;
use core\Middleware\AuthMiddleware;
use core\Request;
use models\User;

class UserController extends Controller
{

	public function __construct()
	{
		$this->registerMiddleware(New AuthMiddleware(['edit', 'update', 'getName']));
	}

	public function index($username)
	{
		$user = new User();

		$user = $user->getOneBy('username', $username);
		if (!$user)
			throw new NotFoundException();
		if (Application::$APP->user) {
			if ($user->username === Application::$APP->user->username)
				return $this->render('pages/profile/myProfile', ['user' => $user], ['title' => Application::$APP->user->name . " - Profile"]);
		}
		return $this->render('pages/profile/profile', ['user' => $user], ['title' => $user->name . " - Profile"]);
	}

	/**
	 * show profile if user is logged otherwise redirect to login
	 */

	public function myProfile()
	{
		if (Application::$APP->user)
			Application::$APP->response->redirect('/user/' . Application::$APP->user->username);
		else
			Application::$APP->response->redirect(Application::path('auth.login'));
	}

	public function edit()
	{
		return $this->render('pages/profile/updateProfile', ['user' => Application::$APP->user], ['title' => Application::$APP->user->name . " - Edit Profile"]);
	}

	public function update(Request $request)
	{
		$user = Application::$APP->user;

		$user->loadData($request->getBody());

		$user->validate();

		$valid = 1;

		if (sizeof($user->errors) == 1 && !isset($user->errors['password']))
			$valid = 0;
		if ($valid && $user->update()) {
			Application::$APP->session->setFlash('success', 'Your information has been updated');
			return Application::$APP->response->redirect(Application::path('user.profile'));
		}

		return $this->render('pages/profile/updateProfile', ['user' => $user], ['title' => Application::$APP->user->name . " - Edit Profile"]);
	}

	public function getName()
	{
		return Application::$APP->user->name;
	}
}