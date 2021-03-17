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

	/**
	 * @return false|string|string[]
	 */
	public function register()
	{
		return $this->render('register');
	}

	public function signup(Request $request)
	{
		$body = $request->getBody();
		if (!empty($body['email']))
			return $this->render('messages/register_email', ['email' => $body['email']]);
		return $this->render('register');
	}


}