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

/**
 * Class AuthController for the authentications
 */

include_once "Controller.php";
include_once Application::$ROOT_DIR . '/models/User.php';

class AuthController extends Controller
{
	/**
	 * @return false|string|string[]
	 */
	public function login()
	{
		$this->setLayout('auth');

		return $this->render('login');
	}

	/**
	 * @param Request $request
	 */
	public function auth(Request $request)
	{
		$body = $request->getBody();
		var_dump($body);
		// todo change this after creating database and db instance
	}

	// todo please fix this repeated code in this file it's so bad

	/**
	 * @return false|string|string[]
	 */
	public function signup()
	{
		return $this->render('register');
	}

	/**
	 * @param Request $request
	 * @return false|string|string[]
	 */

	public function verifyEmail(Request $request)
	{
		$body = $request->getBody();
		if (!empty($body['email']))
			return $this->render('messages/register_email', ['email' => $body['email']]);
		//todo save the email to the database or session

		return $this->render('register');
	}

	/**
	 * @param Request $request
	 * @return false|string|string[]
	 */

	public function register(Request $request)
	{
		$verification = intval($request->getBody()['verification'] ?? 0);

		// todo change the below condition to check with the database;
		// todo retrieve the email and pass it as param
		$email = 'example@email.com';
		if ($verification) {
			return $this->render('forms/register', ['email' => $email, 'user' =>New User()]);
		}

		return $this->render('messages/register_email', [
			'email' => $email,
			'error' => 'Wrong Verification Code'
		]);
	}

	// todo delete this method

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

	public function insertUser(Request $request)
	{
		$user = New User();
		$user->loadData($request->getBody());

		$user->setEmail('example@email.com');
		if ($user->validate() && $user->save())
			return "Success";

		return $this->test($request, $user);
	}

}