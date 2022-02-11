<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   AuthController.php                                :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/03/17 12:02:39 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/03/17 12:02:39 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

namespace Controller;



use DateTime;
use Middlewares\AuthMiddleware;
use Middlewares\GuestMiddleware;
use Model\EmailToken;
use Model\LoginForm;
use Model\Password_reset;
use Model\User;
use Simfa\Action\Controller;
use Simfa\Framework\Application;
use Simfa\Framework\Exception\ExpiredException;
use Simfa\Framework\Request;
use Simfa\Model\UserToken;

/**
 * Class AuthController for the authentications
 */

class AuthController extends Controller
{

	public function __construct()
	{
		$this->registerMiddleware(New AuthMiddleware(['updatePassword']));
		$this->registerMiddleware(New GuestMiddleware(['restore', 'signup', 'logoutMessage']));
	}

    /** render login form view
     * @route('get' => '/login')
	 * @return false|string|string[]
	 */
	public function login()
	{
		if (!Application::isGuest())
			return Application::$APP->response->redirect('/');

		$savedUsers = unserialize(Application::$APP->cookie->get('user'));

		$users = array();
		foreach ((array)$savedUsers as $savedUser) {
			$token = new UserToken();
			$token->getOneBy('token', $savedUser);
			if ($token->entityID)
				array_push($users, $token);
		}

		return render('login', [
				'user' => New User(),
				'users' => $users,
				'title' => 'Login'
			]
		);
	}

	/**
	 * @throws ExpiredException
	 */
	public function magicLogin(UserToken $userToken)
	{
		if (!$userToken->used) {
			$userToken->used = 1;
			$userToken->update();

			Application::$APP->login($userToken->user, '/');
		}

		throw new ExpiredException('this login token has expired');
	}

	/** this the method responsible for handling the login attempts
	 * checks if everything cool let him in
	 * @route('post' => '/login')
	 * @param LoginForm $loginForm
	 * @param Request $request
	 * @return false|string|string[]
	 */

	public function auth(LoginForm $loginForm, Request $request)
	{
		$loginForm->loadData($request->getBody());

		if (!($loginForm->validate() && $loginForm->login($_GET['ref'] ?? '/')))
			return render('login', ['user' => $loginForm]);
		exit(1);
	}

	/** render the signup form
     * @route('get' => '/signup')
	 * @return false|string|string[]
	 */
	public function signup()
	{
		return render('register', ['user' => New User], ['title' => 'Signup']);
	}

	/** render the view where the user can enter his information
	 * @route('post' => '/signup')
	 * @param Request $request
	 * @param EmailToken $email
	 * @return false|string|string[]
	 */

	public function verifyEmail(Request $request, EmailToken $email)
	{
		$body = $request->getBody();
		if (!empty($body['email'])) {
			/** get info from the user */
			$email->email = $body['email'];
			$email->token = 999;
			$email->validate();

			if (empty($email->errors)) {
				/** check if the email is already on our records */
				$tmp = $email->getOneBy('email', $email->email, 0);
				//Generate a random string.
				$token = openssl_random_pseudo_bytes(16);
				//Convert the binary data into hexadecimal representation.
				$email->token = bin2hex($token);

				if (!$tmp)
					$email->save();
				else {
					$email->entityID = $tmp['entityID'];
					$email->update();
				}

				$data = ['verifyEmail', ['port' => $_SERVER['SERVER_PORT'], 'token' => $email->token]];
				if ($this->mail($email->email, 'Verify Your Email', $data))
					return render('messages/register_email', ['email' => $email->email]);
				else
					return render('messages/register_email_failed');
			}
		}

		return Application::$APP->response->redirect('/signup');
	}

	/** get the verification code sent to the user email if it's valid
	 * give him the form to complete his registration
	 * @route('post' => '/register_step_2')
	 * @param EmailToken $email
	 * @return false|string|string[]
	 * @throws ExpiredException
	 */

	public function register(EmailToken $email)
	{
		$user = New User();

		if (!$email->used) {
			$userExist = $user->getOneBy('email', $email->email, 0);
			$email->used = 1;
			$email->update();

			if (!$userExist) {
				Application::$APP->session->set('user_email', $email->email);
				return $this->render('forms/register', ['email' => $email->email, 'user' => $user]);
			} else {
				$message = 'Email already assigned to another account,<a href="';
				$message .= route('auth.login') . '">login</a> or <a href="';
				$message .= route('auth.restore') . '">reset</a> your password';

				Application::$APP->session->setFlash(
					'error',
					$message
				);
				Application::$APP->response->redirect(Application::path('auth.signup'));
				die('wait you are being redirected to a new page');
			}
		}

		throw new ExpiredException('This Token Has Expired', 400);
	}

	/** saving user to the database
	 * @route('post' => '/registration')
	 * @param Request $request
	 * @param User $user
	 * @return false|string|string[]
	 */
    public function insertUser(Request $request, User $user)
	{
		$user->loadData($request->getBody());

		$user->setEmail($_SESSION['user_email']);
		if ($user->validate() && $user->save()) {
			Application::$APP->session->setFlash('success', 'Your account has been created successfully');
			return Application::$APP->response->redirect('/');
		}

		return render('forms/register', ['email' => $_SESSION['user_email'], 'user' => $user]);
	}

	/**
	 * @param Request $request
	 * @return false|string|string[]
	 * @throws \Exception
	 */

	public function restore(Request $request){
		$password = New Password_reset();

		if ($request->isPost()){
			$password->loadData($request->getBody());
			$password->token = bin2hex(random_bytes(18));
			$password->used = 0;

			if ($password->validate()) {
				$user = New User();

				$user->getOneBy('email', $password->email);

				if ($user->id) {
					$data = ['restorePassword', ['port' => $_SERVER['SERVER_PORT'], 'token' => $password->token]];
					if ($this->mail($password->email, 'Restore your password', $data)){
						$pass = New Password_reset();
						$pass = $pass->getOneBy('email', $password->email);
						if (!$pass)
							$password->save();
						else {
							$password->id = $pass->id;
							$password->update();
						}
					}
				}

				return render('messages/restore_message',
					['email' => $request->getBody()['email'] ?? ''],
					['title' => 'Restore Password']
				);
			}
		}

    	return render('messages/restore_password', ['password' => $password], ['title' => 'Restore Password']);
	}

	/**
	 * @param Password_reset $password_reset
	 * @return false|string|string[]|void
	 * @throws \Exception
	 */
	public function checkToken(Password_reset $password_reset)
	{
		$pass = $password_reset;

		if (!$pass->used) {
			$time = New DateTime(date('Y-m-d H:i:s', time()));
			$passTime = $time->diff(New DateTime($pass->updated_at ?? $pass->created_at));

			if ($passTime->i < 15) {
				$pass->used = 1;
				$pass->update();
				$user = New User();
				$tmp = $user->getOneBy('email', $pass->email);
				$user->loadData((array)$tmp);
				/** json_decode(json_encode($tmp), true) */

				if ($user){
					Application::$APP->session->setFlash('system', '1');
					return Application::$APP->login($user, '/set_new_password');
				}
			}
		}


		return render('messages/expired_token');
	}

	/**
	 * @param Request $request
	 * @return false|string|string[]
	 */

	public function updatePassword(Request $request)
	{
		if (!Application::$APP->session->getFlash('system'))
			Application::$APP->response->redirect('/');

		$user = Application::$APP->user;

		$user->password = '';
		if ($request->isPost()) {
			$user->loadData($request->getBody());
			if ($user->validate()) {
				$tmp =  Application::$APP->user;
				$user->pass = true;
				if (password_verify($user->password, $tmp->password))
					$user->addError('password' , 'New Password can\'t be the same as the old one');
				else
					if ($user->update()) {
						Application::$APP->session->setFlash('success', 'Password updated');
						Application::$APP->response->redirect('/');
					}
			}

		}

		Application::$APP->session->setFlash('system', '1');
		return render('messages/setPassword', ['user' => $user]);
	}

	/*
	 * log out method
	 */
	public function logout()
	{
		Application::logout(route('app.logoutMessage'));
	}

	public function logoutMessage()
	{
		$users = unserialize($_COOKIE['user'] ?? null);

		/** remove current user from the saved users unless he wanted to save */
		foreach ((array)$users as $key => $value) {
			$userToken = new UserToken();
			$userToken->getOneBy('token', $value);
			if ($userToken->entityID && $userToken->user->getId() == Application::$APP->session->get('user_tmp'))
				unset($users[$key]);
		}

		Application::$APP->cookie->set('user', serialize($users), time() + (3600 * 24 * 30));

		return render('messages/logout', ['title' => 'you are logged out']);
	}

	/** todo more work here please
	 * @return mixed|null
	 */
	public function logoutSaveMe()
	{
		$userId = Application::$APP->session->get('user_tmp');

		if ($userId) {
			$token = new UserToken();
			$token->getOneBy('user', $userId);
			$users = unserialize($_COOKIE['user'] ?? null);
			if (!$users)
				$users = array($token->token);
			else
				array_push($users, $token->token);

			Application::$APP->cookie->set('user', serialize($users), time() + (3600 * 24 * 30));
			Application::$APP->session->unset('user_tmp');
		}
		redirect('/');

		return $userId;
	}
}
