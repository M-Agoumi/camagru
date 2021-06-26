<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   index.php                                         :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/03/16 17:05:43 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/03/17 09:13:14 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

/**
 * coded on php 7.4.3 environment compatible with php 8.0*
 * (*)has not been tested on it yet
 *
 * let make our code professional by declaring strict Type
 */

//declare(strict_types=1);

/**
 * set cookies to httponly to protect from XSS
 */
ini_set('session.cookie_httponly', 1);

/**
 * Getting global setting from the env file
 * enable error reporting if the env is dev
 */

$config = parse_ini_file(__DIR__ . "/../.env");
if (isset($config['env']) && $config['env'] === 'dev')
	error_reporting(E_ALL ^ E_DEPRECATED);
else
	error_reporting(0);

/**
 * require our autoloader
 */

require_once "../__autoload.php";

use core\Application;

/**
 * creating a new instance of the Application class
 */

$app = new Application(dirname(__DIR__));

/**
 * run our application
 */
$app->run();
