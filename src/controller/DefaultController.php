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
use core\Request;
use Middlewares\AuthMiddleware;
use models\ContactUs;
use models\Post;
use models\User;

/**
 * Class DefaultController
 */

class DefaultController extends Controller
{

	/** home view to be modified
	 * @return string
	 */
	public function index(Post $post): string
	{
		$params = [
			'title' => "Home",
			'postModule' => $post,
			'posts' => $post->paginate()
		];

		return render('home', $params);
	}

	/** contact us page
	 * @Route("get/post","/contact")
	 * @param Request $request
	 * @return false|string|string[]
	 */
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
		}

		return render('forms/contactUs', ['contact' => $contact], ['title' => 'Contact Us']);
	}
}
