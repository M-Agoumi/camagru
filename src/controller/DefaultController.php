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
			'test' => 'yohoo'
		];

		return $this->render('home', $params, ['title' => 'Home']);
	}

}