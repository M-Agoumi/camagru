<?php

use controller\AuthController;
use controller\DefaultController;
use core\Application;


$app = Application::$APP;

/**
 * all the routes of our application
 */

/** default Controller routes */
$app->router->get('/', [DefaultController::class, 'index'])->name('home.index');

/** Auth Controller routes */
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

