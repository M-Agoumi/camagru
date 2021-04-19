<?php

use controller\AuthController;
use controller\CameraController;
use controller\DefaultController;
use controller\PostController;
use controller\UserController;
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
$app->router->post('/logout', [AuthController::class, 'logout'])->name('app.logout');

/** User Controller routes */
$app->router->magic('/user/{username}', [UserController::class, 'index']);
$app->router->get('/profile', [UserController::class, 'myProfile'])->name('user.profile');
$app->router->get('/profile/', [UserController::class, 'myProfile']);
$app->router->get('/me', [UserController::class, 'myProfile']);
$app->router->get('/user/me', [UserController::class, 'myProfile']);
/** todo edit user page */

/** Camera Controller routes */
$app->router->get('/camera', [CameraController::class, 'index'])->name('camera.index');
$app->router->post('/camera', [CameraController::class, 'save'])->name('camera.save');
$app->router->post('/camera/share', [CameraController::class, 'share'])->name('camera.share');
$app->router->magic('/post/{slug}', [PostController::class, 'show']);

/** debug routes */
$app->router->post('/testing', [AuthController::class, 'auth2'])->name('auth.auth2');
$app->router->get('/debugger', function (){return $_SESSION['email_code'] ?? 'no code found';});
$app->router->get('/session', function() {var_dump($_SESSION);});
$app->router->magic('/test/[id]', [DefaultController::class, 'test', 'id']);
$app->router->magic('/user/{id}', [DefaultController::class, 'user']);
