<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   DefaultController.php                             :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/03/17 10:13:09 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/03/17 10:13:09 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

namespace controller;

use core\Application;
use core\Middleware\AuthMiddleware;
use core\Request;
use models\ContactUs;
use models\Post;
use models\User;

/**
 * Class DefaultController
 */

class DefaultController extends Controller
{

    public function __construct()
    {
        $this->registerMiddleware(New AuthMiddleware(['profile']));
    }
	/** home view to be modified
	 * @return string
	 */
	public function index(): string
	{
		$params = [
			'name' => "Magoumi",
			'title' => "Home",
			'test' => 'yohoo',
			'postModule' => New Post()
		];

		return $this->render('home', $params, ['title' => 'Home']);
	}

	public function test(Request $request)
	{
		$user = New User();
		if ($request->isPost()){
			$user->loadData($request->getBody());
		}

		return $this->render('test', ['user' => $user]);
	}

	public function contactUs(Request $request)
	{
		$contact = New ContactUs();

		if ($request->isPost()) {
			$contact->loadData($request->getBody());

			$contact->logged = !Application::isGuest();
			$contact->user = $contact->logged ? Application::$APP->user->getId() : NULL;

			if (!$contact->logged && empty($contact->email))
				$contact->addError('email', 'this field is required');

			$contact->status = 1;

			if ($contact->validate() && $contact->save()) {
				Application::$APP->session->setFlash('success', 'Message sent successfully');
				Application::$APP->response->redirect('/');
			}

			var_export($contact);

		}

		return $this->render('forms/contactUs', ['contact' => $contact], ['title' => 'Contact Us']);
	}

	/** a security breach to update password to any account cause im done with resetting my password everyday :)
	 * todo remove this method
	 * @param Request $request
	 * @return false|string|string[]
	 */
	public function password(Request $request)
	{
		$user = new User();

		if ($request->isPost()) {
			$user->loadData($request->getBody());

			$password = $user->password;

			$updatedUser = $user->getOneBy('email', $user->email, 0);

			$user->loadData((array)$updatedUser);

			$user->password = $password;
			$user->pass = true;

			if ($user->update())
				return 'done';

			return 'something went wrong';
		}

		return $this->render('pages/dev/resetPassword', ['user' => $user]);
	}
}