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

namespace Controller;

use Exception;
use Simfa\Action\Controller;
use Simfa\Framework\Application;
use Simfa\Framework\Request;
use Model\ContactUs;
use Model\Post;
use Model\User;

/**
 * Class DefaultController
 */

class DefaultController extends Controller
{

	/** fetch posts from the latest to the older
	 * @param Post $post injectDependencies to use its methods
	 * @return string
	 */
	public function index(Post $post): string
	{
		$params = [
			'title' => "Home",
			'postModule' => $post
		];

		return render('home', $params);
	}

	/** contact us page
	 * @Route("get/post","/contact")
	 * @param Request $request
	 * @return string
	 * @throws Exception
	 */
	public function contactUs(Request $request): string
	{
		$contact = New ContactUs();

		if ($request->isPost()) {
			$contact->loadData($request->getBody());

			$contact->setLogged(!Application::isGuest());
			$contact->setUser($contact->getLogged() ? Application::$APP->user : NULL);

			if ($contact->getLogged())
				$contact->setEmail(Application::$APP->user->getEmail());

			$contact->setStatus(1);

			if ($contact->validate() && $contact->save()) {
				Application::$APP->session->setFlash('success', 'Message sent successfully');
				Application::$APP->response->redirect('/');
			}
		}

		return render('forms/contactUs', ['contact' => $contact], ['title' => 'Contact Us']);
	}
}
