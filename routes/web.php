<?php

use controller\PostCommentController;
use core\Router;
use controller\AuthController;
use controller\CameraController;
use controller\DefaultController;
use controller\PostController;
use controller\UserController;
use core\Application;


/**
 * all the routes of our application
 */

/** default Controller routes */
Router::get('/', [DefaultController::class, 'index'])->name('home.index');
Router::get('/contactus', [DefaultController::class, 'contactUs'])->name('contact.us');
Router::post('/contactus', [DefaultController::class, 'contactUs']);
Router::get('/testa', [DefaultController::class, 'test']);
Router::post('/testa', [DefaultController::class, 'test']);

/** Auth Controller routes */
Router::get('/login', [AuthController::class, 'login'])->name("auth.login");
Router::post('/login', [AuthController::class, 'auth'])->name('auth.auth');
Router::get('/signup', [AuthController::class, 'signup'])->name('auth.signup');
Router::post('/signup', [AuthController::class, 'verifyEmail'])->name('auth.verifyEmail');
Router::post('/register_step_2', [AuthController::class, 'register'])->name('auth.register');
Router::get('/register_step_2', [AuthController::class, 'test'])->name('auth.test');
Router::post('/registration', [AuthController::class, 'insertUser'])->name('auth.insertUser');
Router::post('/logout', [AuthController::class, 'logout'])->name('app.logout');
Router::get('/restore_password', [AuthController::class, 'restore']);
Router::post('/restore_password', [AuthController::class, 'restore'])->name('auth.restore');
//Router::magic("/verifyToken/{token}", [AuthController::class, 'checkToken']);
Router::get("/set_new_password", [AuthController::class, 'updatePassword'])->name('auth.updatePassword');
Router::post("/set_new_password", [AuthController::class, 'updatePassword']);

/** User Controller routes */
Router::magic('/user/{username}', [UserController::class, 'index']);
Router::get('/profile', [UserController::class, 'myProfile'])->name('user.profile');
Router::get('/profile/', [UserController::class, 'myProfile']);
Router::get('/me', [UserController::class, 'myProfile']);
Router::get('/user/me', [UserController::class, 'myProfile']);
Router::get('/profile/edit', [UserController::class, 'edit'])->name('user.edit');
Router::post('/profile/edit', [UserController::class, 'update'])->name('user.update');
Router::get('/profile/edit/password', [UserController::class, 'UpdatePassword'])->name('user.update.password');
Router::post('/profile/edit/password', [UserController::class, 'UpdatePassword']);
Router::get('/profile/preferences', [UserController::class, 'preferences'])->name('user.preferences');
Router::post('/profile/preferences', [UserController::class, 'preferences']);

/** Camera Controller routes */
Router::get('/camera', [CameraController::class, 'index'])->name('camera.index');
Router::post('/camera', [CameraController::class, 'save'])->name('camera.save');
Router::post('/camera/share', [CameraController::class, 'share'])->name('camera.share');

/** post Controller routes */
Router::magic('/post/{slug}', [PostController::class, 'show']);
Router::magic('/post/like/{id}', [PostController::class, 'like'])->name('post.like');

/** API routes */
//Router::magic('/api/post/likes/{id}', [PostController::class, 'showLikes']); /** post fetch like */
Router::magic('/api/post/comment/{slug}', [PostCommentController::class, 'add']); /** post add comments */
Router::post('/api/user/name', [UserController::class, 'getName']); /** get logged user name */

/** debug routes */
Router::post('/testing', [AuthController::class, 'auth2'])->name('auth.auth2');
Router::get('/debugger', function (){return $_SESSION['email_code'] ?? 'no code found';});
Router::get('/mailer', function (){return file_get_contents(Application::$ROOT_DIR. '/var/mail.tmp') ?? 'no mail found';});
Router::get('/session', function() {var_dump($_SESSION);});
Router::get('/unset_session', function() {session_destroy();});
Router::get('/test', [DefaultController::class, 'test']);
Router::post('/test', [DefaultController::class, 'test']);
//Router::magic('/user/{id}', [DefaultController::class, 'user']);
Router::get('/dev/set-password', [DefaultController::class, 'password']);
Router::post('/dev/set-password', [DefaultController::class, 'password']);
