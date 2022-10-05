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
use Exception;
use Middlewares\GuestMiddleware;
use Model\Email;
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
		$this->registerMiddleware(New GuestMiddleware(['restore', 'signup', 'logoutMessage']));
	}

    /** render login form view
     * @route('get' => '/login')
	 * @return string
	 */
	public function login(): string
	{
		if (!Application::isGuest())
			return Application::$APP->response->redirect('/');

		$savedUsers = unserialize(Application::$APP->cookie->get('user'));

		$users = array();
		foreach ((array)$savedUsers as $savedUser) {
			$token = new UserToken();
			$token->getOneBy('token', $savedUser);
			if ($token->entityID)
				$users[] = $token;
		}

		return render('auth.login', [
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
	 * @return string
	 * @throws Exception
	 */

	public function auth(LoginForm $loginForm, Request $request): string
	{
		$loginForm->loadData($request->getBody());

		if (!($loginForm->validate() && $loginForm->login($_GET['ref'] ?? '')))
			return render('auth.login', ['title' => 'login', 'user' => $loginForm]);
		return '';
	}

	/** render the signup form
	 * @route('get' => '/signup')
	 * @param Request $request
	 * @param Email $email
	 * @return string
	 * @throws Exception
	 */
	public function signup(Request $request, Email $email): string
	{
		if ($request->isPost()) {
			$body = $request->getBody();
			if (!empty($body['email'])) {
				/** get info from the user */
				$email->setEmail($body['email']) ;
				$email->setToken('999');
				$email->validate();

				if (empty($email->errors)) {
					/** check if the email is already on our records */
					$tmp = $email->getOneBy('email', $email->getEmail(), 0);
					//Generate a random string.
					$token = openssl_random_pseudo_bytes(16);
					//Convert the binary data into hexadecimal representation.
					$email->setToken(bin2hex($token));

					if (!$tmp)
						$email->save();
					else {
						$email->setId($tmp['entityID']);
						$email->update();
					}

					$data = ['verifyEmail', [
						'port'  => $_SERVER['SERVER_PORT'],
						'token' => $email->getToken(),
						'title' =>'Verify Your Email']
					];
					if ($this->mail($email->getEmail(), 'Verify Your Email', $data))
						return render('messages/register_email', ['email' => $email->getEmail()]);
					else
						return render('messages/register_email_failed');
				}
			}
		}

		return render('auth.register', ['user' => New User]);
	}

	/** get the verification code sent to the user email if it's valid
	 * give him the form to complete his registration
	 * @route('post' => '/register_step_2')
	 * @param Email $email
	 * @return string
	 * @throws ExpiredException
	 */

	public function register(Email $email): string
	{
		$user = New User();
		if (!$email->getUsed()) {
			$email->setUsed(1);
			$email->update();

			if (!$email->getUser()->getId()) {
				Application::$APP->session->set('user_email', $email->getEmail());
				return $this->render('forms/register', ['email' => $email->getEmail(), 'user' => $user]);
			} else {
				$message = 'Email already assigned to another account,<b> ';
				$message .= 'login</b> or <a href="' . route('auth.restore') . '">reset</a> your password';

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
	 * @return string
	 * @throws Exception
	 */
    public function insertUser(Request $request, User $user): string
	{
		$user->loadData($request->getBody());

		if ($user->validate() && $user->save()) {
			$email = new Email();
			$email->getOneBy('email', $_SESSION['user_email']);
			$email->setUser($user);
			$email->update();
			Application::$APP->session->setFlash('success', 'Your account has been created successfully');
			return Application::$APP->login($user,'');
		}

		return render('forms/register', ['email' => $_SESSION['user_email'], 'user' => $user]);
	}

	/**
	 * @param Request $request
	 * @return string|null
	 * @throws Exception
	 */
	public function restore(Request $request): ?string
	{
		$password = New Password_reset();

		if ($request->isPost()){
			$password->loadData($request->getBody());
			$password->token = bin2hex(random_bytes(18));
			$password->used = 0;

			if ($password->validate()) {
				$user = New User();

				$email = new Email();
				$email->getOneBy('email', $password->email);
				$user->getOneBy($email->getUser());

				if ($user->getId()) {
					$data = ['restorePassword', ['port' => $_SERVER['SERVER_PORT'], 'token' => $password->token]];
					if ($this->mail($password->email, 'Restore your password', $data)){
						$pass = New Password_reset();
						$pass = $pass->getOneBy('email', $password->email);
						if (!$pass->getId()) {
							$password->save();
						} else {
							$password->setId($pass->getId());
							$password->update();
						}
					}
				}

				return render('messages/restore_message',
					['email' => $request->getBody()['email'] ?? '', 'title' => 'Restore Password']
				);
			}
		}

    	return render('messages/restore_password', ['password' => $password, 'title' => 'Restore Password']);
	}

	/**
	 * @param Password_reset $password_reset
	 * @return false|string|string[]|void
	 * @throws Exception
	 */
	public function updatePassword(Password_reset $password_reset, Request $request)
	{
		$pass = $password_reset;

		$user = New User();
		$email = new Email();
		$email->getOneBy('email', $pass->getEmail());
		$user->getOneBy($email->getUser());

		if ($request->isPost()) {
			return $this->saveNewPassword($request, $user);
		}

		if (!$pass->used) {
			$time = New DateTime(date('Y-m-d H:i:s', time()));
			$passTime = $time->diff(New DateTime($pass->updated_at ?? $pass->created_at));

			if ($passTime->i < 15) {
				$pass->used = 1;
				$pass->update();
				$user->setPassword('');

				if ($user->getId())
					return render('messages/setPassword', ['user' => $user]);
			}
		}

		return render('messages/expired_token');
	}

	/**
	 * @return false|string|string[]
	 */
	private function saveNewPassword(Request $request, User $user): array|bool|string
	{
		$tmp = clone $user;
		try {
			$user->loadData($request->getBody());
		} catch (Exception $exception) {
			Application::$APP->catcher->catch($exception);
		}

		if ($user->validate()) {
			$user->setPass(true);
			if (password_verify($user->getPassword(), $tmp->getPassword()))
				$user->addError('password' , 'New Password can\'t be the same as the old one');
			else
				if ($user->update()) {
					Application::$APP->session->setFlash('success', 'Password updated');
					redirect('/');
				}
		}
		$user->setPassword('');

		return render('messages/setPassword', ['user' => $user]);
	}

	/*
	 * log out method
	 */
	public function logout()
	{
		Application::logout(route('app.logoutMessage'));
	}

	public function logoutMessage(): ?string
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

	/**
	 * @return mixed|null
	 */
	public function logoutSaveMe(): mixed
	{
		$userId = Application::$APP->session->get('user_tmp');

		if ($userId) {
			$token = new UserToken();
			$token->getOneBy('user', $userId);
			$users = unserialize($_COOKIE['user'] ?? null);
			if (!$users)
				$users = array($token->token);
			else
				$users[] = $token->token;

			Application::$APP->cookie->set('user', serialize($users), time() + (3600 * 24 * 30));
			Application::$APP->session->unset('user_tmp');
		}
		redirect('/');

		return $userId;
	}
}
