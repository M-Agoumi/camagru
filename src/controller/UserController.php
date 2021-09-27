<?php


namespace controller;


use core\Application;
use core\Request;
use Middlewares\AuthMiddleware;
use models\core\Languages;
use models\core\Preferences;
use models\User;

class UserController extends Controller
{

	public function __construct()
	{
		$this->registerMiddleware(New AuthMiddleware(['edit', 'update', 'getName', 'preferences']));
	}

	public function index(User $user)
	{
		if (!Application::isGuest())
			if ($user->username === Application::$APP->user->username)
				return $this->render('pages/profile/myProfile', ['user' => $user,
					'title' => Application::$APP->user->name . " - Profile"]);

		return render('pages/profile/profile', ['user' => $user, 'title' => $user->name . " - Profile"]);
	}

	/**
	 * show profile if user is logged otherwise redirect to log in
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
		return render('pages/profile/updateProfile', [
			'user' => Application::$APP->user,
			'title' => Application::$APP->user->name . " - Edit Profile"
		]);
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

		return render('pages/profile/updateProfile', [
			'user' => $user,
			'title' => Application::$APP->user->name . " - Edit Profile"
		]);
	}

	public function UpdatePassword(Request $request)
	{
		$user = Application::$APP->user;

		if ($request->isPost()) {
		    $newUser = clone $user;

		    $newUser->loadData($request->getBody());

		    if (password_verify($newUser->password, $user->password)) {
		    	$password = filter_input(INPUT_POST, "newPassword", FILTER_SANITIZE_SPECIAL_CHARS);
		    	$retypePassword = filter_input(INPUT_POST, "retypePassword", FILTER_SANITIZE_SPECIAL_CHARS);

		    	$user->password = $password;
		    	$user->pass = true;

		    	if ($password === $retypePassword) {
		    		if ($user->validate()) {
					    if ($user->update()) {
					        Application::$APP->session->setFlash('success', 'Password Updated');
					        Application::$APP->response->redirect('/user/' . $user->username);
					    } else
						    echo "something went wrong";
				    } else {
					    unset($user->errors['password']);
					    $user->addError('newPassword', 'Password is not valid');
				    }
			    } else {
				    $user->addError('retypePassword', "new password doesn't match");
			    }
		    } else
		    	$user->addError('password', 'password is wrong');
		}
		$user->password = '';

		return render('pages/profile/updatePassword', ['user' => $user, 'title' => 'Update Password']);
	}


	public function preferences(Request $request)
	{
		$pref = New Preferences();
		$user = Application::$APP->user->getId();

		$pref->getOneBy('user', $user);

		if ($request->isPost()) {
			$pref->loadData($request->getBody());

			if ($pref->id) {
				if ($pref->validate() && $pref->update()){
					Application::$APP->session->set('lang_main', (languages::getLang($pref->language))->language);
					Application::$APP->session->set('lang_fb', (languages::getLang($pref->language))->language);
					Application::$APP->session->setFlash('success', 'your preferences has been updated');
					Application::$APP->response->redirect(Application::path('user.preferences'));
				}
			} else {
				$pref->user = $user;

				if ($pref->validate() && $pref->save()) {
					Application::$APP->session->set('lang_main', (languages::getLang($pref->language))->language);
					Application::$APP->session->setFlash('success', 'your preferences has been updated');
					Application::$APP->response->redirect(Application::path('user.preferences'));
				}
			}
		}

		return render('pages/profile/preferences', ['pref' => $pref, 'title' => 'Preferences'] );
	}

	public function getName()
	{
		return Application::$APP->user->name;
	}
}
