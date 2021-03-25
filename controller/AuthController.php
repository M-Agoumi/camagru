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
	/** render login form view
     * @route('get' => '/login')
	 * @return false|string|string[]
	 */
	public function login()
	{
		$this->setLayout('auth');

		return $this->render('login');
	}

	/** this the method responsible for handling the login attempts
     * checks if everything cool let him in
     * @route('post' => '/login')
	 * @param Request $request
	 */
	public function auth(Request $request)
	{
		$body = $request->getBody();
		var_dump($body);
		// todo change this after creating database and db instance
	}


	// todo please fix this repeated code in this file it's so bad

	/** render the signup form
     * @route('get' => '/signup')
	 * @return false|string|string[]
	 */
	public function signup()
	{
		return $this->render('register');
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
		if (!empty($body['email']))
			return $this->render('messages/register_email', ['email' => $body['email']]);

		return $this->render('register');
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
		$email = 'example@email.com';
		if ($verification) {
			return $this->render('forms/register', ['email' => $email, 'user' =>New User()]);
		}

		return $this->render('messages/register_email', [
			'email' => $email,
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

		$user->setEmail('example@email.com');
		if ($user->validate() && $user->save())
			return "Success";

		return $this->test($request, $user);
	}

}