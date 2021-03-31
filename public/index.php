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
 * Getting global setting from the env file
 * enable error reporting if the env is dev
 */

$config = parse_ini_file(__DIR__ ."/../.env");
if (isset($config['env']) && $config['env'] === 'dev')
	error_reporting(E_ALL ^ E_DEPRECATED);
else
	error_reporting(0);

/**
 * require our app class the heart of our application
 */
require_once __DIR__ . "/../core/Application.php";

/**
 * creating a new instance of the Application class
 */

$app = New Application(dirname(__DIR__));


/**
 * include our controllers so we can send their methods
 * @VAR $ROOT_DIR string ../
 */

include_once $app::$ROOT_DIR . "/controller/DefaultController.php";
include_once $app::$ROOT_DIR . "/controller/AuthController.php";

/**
 * the routes of our application
 */
$app->router->get('/', [DefaultController::class, 'index'])->name('home.index');
$app->router->get('/login', [AuthController::class, 'login'])->name("auth.login");
$app->router->post('/login', [AuthController::class, 'auth'])->name('auth.auth');
$app->router->get('/signup', [AuthController::class, 'signup'])->name('auth.signup');
$app->router->post('/signup', [AuthController::class, 'verifyEmail'])->name('auth.verifyEmail');
$app->router->post('/register_step_2', [AuthController::class, 'register'])->name('auth.register');
$app->router->get('/register_step_2', [AuthController::class, 'test'])->name('auth.test');
$app->router->post('/registration', [AuthController::class, 'insertUser'])->name('auth.insertUser');
$app->router->post('/testing', [AuthController::class, 'auth2'])->name('auth.auth2');
$app->router->post('/logout', [AuthController::class, 'logout'])->name('app.logout');

$app->router->get('/debugger', function (){return $_SESSION['email_code'];});


/**
 * run our application
 */
$app->run();
