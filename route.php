<?php

use controller\AuthController;
use controller\CameraController;
use controller\DefaultController;
use controller\PostCommentController;
use controller\PostController;
use controller\UserController;
use core\Application;


$app = Application::$APP;

/**
 * all the routes of our application
 */

/** default Controller routes */
$app->router->get('/', [DefaultController::class, 'index'])->name('home.index');
$app->router->get('/testa', [DefaultController::class, 'test']);
$app->router->post('/testa', [DefaultController::class, 'test']);

/** Auth Controller routes */
$app->router->get('/login', [AuthController::class, 'login'])->name("auth.login");
$app->router->post('/login', [AuthController::class, 'auth'])->name('auth.auth');
$app->router->get('/signup', [AuthController::class, 'signup'])->name('auth.signup');
$app->router->post('/signup', [AuthController::class, 'verifyEmail'])->name('auth.verifyEmail');
$app->router->post('/register_step_2', [AuthController::class, 'register'])->name('auth.register');
$app->router->get('/register_step_2', [AuthController::class, 'test'])->name('auth.test');
$app->router->post('/registration', [AuthController::class, 'insertUser'])->name('auth.insertUser');
$app->router->post('/logout', [AuthController::class, 'logout'])->name('app.logout');
$app->router->get('/restore_password', [AuthController::class, 'restore']);
$app->router->post('/restore_password', [AuthController::class, 'restore'])->name('auth.restore');
$app->router->magic("/verifyToken/{token}", [AuthController::class, 'checkToken']);
$app->router->get("/set_new_password", [AuthController::class, 'updatePassword'])->name('auth.updatePassword');
$app->router->post("/set_new_password", [AuthController::class, 'updatePassword']);

/** User Controller routes */
$app->router->magic('/user/{username}', [UserController::class, 'index']);
$app->router->get('/profile', [UserController::class, 'myProfile'])->name('user.profile');
$app->router->get('/profile/', [UserController::class, 'myProfile']);
$app->router->get('/me', [UserController::class, 'myProfile']);
$app->router->get('/user/me', [UserController::class, 'myProfile']);
$app->router->get('/profile/edit', [UserController::class, 'edit'])->name('user.edit');
$app->router->post('/profile/edit', [UserController::class, 'update'])->name('user.update');
/** todo edit user page */

/** Camera Controller routes */
$app->router->get('/camera', [CameraController::class, 'index'])->name('camera.index');
$app->router->post('/camera', [CameraController::class, 'save'])->name('camera.save');
$app->router->post('/camera/share', [CameraController::class, 'share'])->name('camera.share');

/** post Controller routes */
$app->router->magic('/post/{slug}', [PostController::class, 'show']);
$app->router->magic('/post/like/{id}', [PostController::class, 'like'])->name('post.like');

/** API routes */
$app->router->magic('/api/post/likes/{id}', [PostController::class, 'showLikes']); /** post fetch like */
$app->router->magic('/api/post/comment/{slug}', [PostCommentController::class, 'add']); /** post add comments */
$app->router->post('/api/user/name', [UserController::class, 'getName']); /** get logged user name */

/** debug routes */
$app->router->post('/testing', [AuthController::class, 'auth2'])->name('auth.auth2');
$app->router->get('/debugger', function (){return $_SESSION['email_code'] ?? 'no code found';});
$app->router->get('/mailer', function (){return file_get_contents(Application::$ROOT_DIR. '/var/mail.tmp') ?? 'no mail found';});
$app->router->get('/session', function() {var_dump($_SESSION);});
$app->router->get('/test', [DefaultController::class, 'test']);
$app->router->post('/test', [DefaultController::class, 'test']);
$app->router->magic('/user/{id}', [DefaultController::class, 'user']);
