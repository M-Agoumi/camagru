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

namespace controller;

use core\Application;
use core\Middleware\AuthMiddleware;
use core\Request;
use DateTime;
use models\LoginForm;
use models\Password_reset;
use models\User;

/**
 * Class AuthController for the authentications
 */


class AuthController extends Controller
{

	public function __construct()
	{
		$this->registerMiddleware(New AuthMiddleware(['updatePassword']));
	}

    /** render login form view
     * @route('get' => '/login')
	 * @return false|string|string[]
	 */
	public function login()
	{
		if (!Application::isGuest())
			return Application::$APP->response->redirect('/');
		$this->setLayout('auth');

		return $this->render('login', ['user' => New User()], ['title' => 'Login']);
	}
	
	/** this the method responsible for handling the login attempts
	 * checks if everything cool let him in
	 * @route('post' => '/login')
	 * @param Request $request
	 * @return false|string|string[]|void
	 */
	public function auth(Request $request)
	{
		$body = $request->getBody();
		$user = new User();
        $record = $user->getOneBy('username', $body['username']);
		if (!$record)
		    $record = $user->getOneBy('email', $body['username']);
        $user->loadData((array)$record);
		if ($user)
		    if (password_verify($body['password'], $user->password))
		        return var_dump($user);
		// todo save to session after creating it
        unset($user);
        $user = New User();
        $user->username = $body['username'];
        $user->addError('username' ,'');
        $user->addError('password' , 'Username or password is wrong');
        return $this->render('login', ['auth' => 1,'user' => $user]);
	}
	
	public function auth2(Request $request)
	{
		$loginForm = New LoginForm();
		$loginForm->loadData($request->getBody());

		if ($loginForm->validate() && $loginForm->login($_GET['ref'] ?? '/')) {

		}
		return $this->render('login', ['user' => $loginForm]);
	}


	// todo please fix this repeated code in this file it's so bad

	/** render the signup form
     * @route('get' => '/signup')
	 * @return false|string|string[]
	 */
	public function signup()
	{
		return $this->render('register', ['user' => New User], ['title' => 'Signup']);
	}

	/** todo save the email to the database or session and send verification code
     * then render the view where the user can enter his verification code
     * @route('post' => '/signup')
	 * @param Request $request
	 * @return false|string|string[]
	 */

	public function verifyEmail(Request $request)
	{
		$body = $request->getBody();
		if (!empty($body['email'])) {
			/** todo check if email is valid */
			$_SESSION['user_email'] = $body['email'];
			$_SESSION['email_code'] = rand(100000,999999);
			return $this->render('messages/register_email');
		}

		return Application::$APP->response->redirect('/signup');
	}

	/** get the verification code sent to the user email if it's valid
     * give him the form to complete his registration
     * @route('post' => '/register_step_2')
	 * @param Request $request
	 * @return false|string|string[]
	 */

	public function register(Request $request)
	{
		$verification = intval($request->getBody()['verification'] ?? 0);

		// todo change the below condition to check with the database;
		// todo retrieve the email and pass it as param
		$code = $_SESSION['email_code'] ?? 0;
		if ($verification == $code && $code) {
			$user = New User();
			if (!$user->getOneBy('email', $_SESSION['user_email']))
				return $this->render('forms/register', ['email' => $_SESSION['user_email'], 'user' => $user]);
			else {
				Application::$APP->session->setFlash(
					'error',
					'Email already assigned to another account, login or reset your password'
				);
				Application::$APP->response->redirect(Application::path('auth.signup'));
				return 'wait you are being redirected to a new page';
			}
		}

		return $this->render('messages/register_email', [
			'email' => $_SESSION['user_email'],
			'error' => 'Wrong Verification Code'
		]);
	}

    /** just a temporary method to handle the get access to our registration form
     * it should be accessible only after verifying your email with a single use token
     * you can create an account right after you validate your email
     * todo delete this method when you implement the above
     * @route('get' => '/register_step_2')
     * @param Request $request
     * @param User|null $user
     * @return false|string|string[]
     */
    public function test(Request $request, User $user = null)
	{
		if (!$user)
			$user = New User();
		// $user->email = 'example@email.com';
		return $this->render('forms/register', [
			'email' => 'example@email.com',
			'user' => $user
		]);
	}

    /** saving user to the database todo change return type from string to view
     * @route('post' => '/registration')
     * @param Request $request
     * @return false|string|string[]
     */
    public function insertUser(Request $request)
	{
		$user = New User();
		$user->loadData($request->getBody());

		$user->setEmail($_SESSION['user_email']);
		if ($user->validate() && $user->save()) {
			Application::$APP->session->setFlash('success', 'Your account has been created successfully');
			return Application::$APP->response->redirect('/');
		}

		return $this->test($request, $user);
	}

	/**
	 * @param Request $request
	 * @return false|string|string[]
	 */

	public function restore(Request $request){
		if (Application::$APP->user)
			Application::$APP->response->redirect('/');

		$password = New Password_reset();
		if ($request->isPost()){
			$password->loadData($request->getBody());
			$password->token = bin2hex(random_bytes(18));
			$password->used = 0;
			if ($password->validate()) {
				$user = New User();

				$user = $user->getOneBy('email', $password->email);

				if ($user) {
					if ($this->mail($password->email, $password->token)){
						$pass = New Password_reset();
						/** @var  $pass Password_reset */
						$pass = $pass->getOneBy('email', $password->email);
						if ($pass->used)
							$password->save();
						else {
							$password->id = $pass->id;
							$password->update();
						}
					}
				}

				return $this->render('messages/restore_message',
					['email' => $request->getBody()['email'] ?? ''],
					['title' => 'Restore Password']
				);
			}
		}

    	return $this->render('messages/restore_password', ['user' => $password], ['title' => 'Restore Password']);
	}

	/**
	 * @param $token
	 * @return false|string|string[]|void
	 * @throws \Exception
	 */
	public function checkToken($token)
	{
		$pass = New Password_reset();
		$pass = $pass->findOne(['token' => $token, 'used' => 0]);

		if ($pass) {
			if (!$pass->used) {
				$time = New DateTime(date('Y-m-d H:i:s', time()));
				$passTime = $time->diff(New DateTime($pass->updated_at ?? $pass->created_at));

				if ($passTime->i < 60) {
					$pass->used = 1;
					$pass->update();
					$user = New User();
					$tmp = $user->getOneBy('email', $pass->email);
					$user->loadData(json_decode(json_encode($tmp), true));

					if ($user){
						Application::$APP->session->setFlash('system', '1');
						return Application::$APP->login($user, '/set_new_password');
					}
				}
			}
		}

		return $this->render('messages/expired_token');
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
				$tmp =  $user->getOneBy('id', $user->id);
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
		return $this->render('messages/setPassword', ['user' => $user]);
	}

	/*
	 * log out method
	 */
	public function logout()
	{
		Application::logout();
	}
}