<?php



/**
 * all the routes of our application
 */

/** error pages */

use Controller\Admin\DashboardController;
use Controller\ApiController;
use Controller\AuthController;
use Controller\CameraController;
use Controller\DefaultController;
use Controller\PostCommentController;
use Controller\PostController;
use Controller\TestController;
use Controller\UserController;
use Simfa\Framework\Application;
use Simfa\Framework\Router;

Router::get('/javascript-disabled', 'error.__noJavascript');

/** default Controller routes */
Router::get('/', [DefaultController::class, 'index'])->name('home.index');
Router::get('/contactus', [DefaultController::class, 'contactUs'])->name('contact.us');
Router::post('/contactus', [DefaultController::class, 'contactUs']);

/** Auth Controller routes */
Router::get('/login', [AuthController::class, 'login'])->name("auth.login");
Router::post('/login', [AuthController::class, 'auth'])->name('auth.auth');
Router::magic('/magic-login/{token}', [AuthController::class, 'magicLogin'])->name('auth.magic.login');

Router::get('/signup', [AuthController::class, 'signup'])->name('auth.signup');
Router::post('/signup', [AuthController::class, 'verifyEmail'])->name('auth.verifyEmail');
Router::magic('/verify-email/{token}', [AuthController::class, 'register'])->name('auth.register');
Router::post('/registration', [AuthController::class, 'insertUser'])->name('auth.insertUser');

Router::get('/restore_password', [AuthController::class, 'restore']);
Router::post('/restore_password', [AuthController::class, 'restore'])->name('auth.restore');
Router::magic("/verify-token/{token}", [AuthController::class, 'checkToken']);
Router::get("/set_new_password", [AuthController::class, 'updatePassword'])->name('auth.updatePassword');
Router::post("/set_new_password", [AuthController::class, 'updatePassword']);

Router::post('/logout', [AuthController::class, 'logout'])->name('app.logout');
Router::get('/logout-message', [AuthController::class, 'logoutMessage'])->name('app.logoutMessage');
Router::get('/logout-save-me', [AuthController::class, 'logoutSaveMe'])->name('app.logout.save');

/** User Controller routes */
Router::magic('/user/{username}', [UserController::class, 'index']);
Router::get('/profile', [UserController::class, 'myProfile'])->name('user.profile');
Router::get('/me', [UserController::class, 'myProfile']);
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
Router::magic('/post/like/{entityID}', [PostController::class, 'like'])->name('post.like');

/** API routes */
Router::post('/posts', [ApiController::class, 'posts']);
Router::magic('/api/post/likes/{entityID}', [PostController::class, 'showLikes']); /** post fetch like */
Router::magic('/api/post/comment/{slug}', [PostCommentController::class, 'add']); /** post add comments */
Router::post('/api/user/name', [UserController::class, 'getName']); /** get logged user name */
Router::get('/api/maincolor', function() {return '#FFF';});

/** Admin dashboard routes */
Router::get('/dashboard', [DashboardController::class, 'index']);
Router::get('/dashboard/emotes', [DashboardController::class, 'emotes']);
Router::request('/dashboard/emotes/new', [DashboardController::class, 'addEmote']);
Router::magic('/dashboard/emotes/delete/{entityID}', [DashboardController::class, 'DeleteEmote']);
Router::get('/dashboard/users', [DashboardController::class, 'users']);
Router::magic('/dashboard/users/delete/{entityID}', [DashboardController::class, 'DeleteUser']);
Router::get('/dashboard/posts', [DashboardController::class, 'posts']);
Router::magic('/dashboard/posts/delete/{entityID}', [DashboardController::class, 'DeletePost']);

/** debug routes */
Router::get('/dev/code', function (){return $_SESSION['email_code'] ?? 'no code found';});
Router::get('/session', function() {echo "<pre>"; var_dump($_SESSION); return '';});
Router::get('/unset_session', function() {session_destroy();});
Router::magic('/user/{id}', [DefaultController::class, 'user']);
Router::get('/dev/set-password', [TestController::class, 'password']);
Router::post('/dev/set-password', [TestController::class, 'password']);
Router::magic('/dev/link/{var}', [TestController::class, 'linkVar']);
Router::get('/canvas', [TestController::class, 'imageCanvas']);
Router::get('/mailer', [TestController::class, 'mailTest']);
Router::get('/autowire', [TestController::class, 'autoWire']);
Router::magic('/abah/{id}', [TestController::class, 'autoFetch']);
Router::get('/phpinfo', [TestController::class, 'phpinfo']);
Router::get('/pagination', [TestController::class, 'pagination']);
Router::get('/view', [TestController::class, 'viewEngine']);
Router::get('/loginChecker', function (){
	return Application::isGuest();
});
Router::get('/mailview', [TestController::class, 'emailView']);
Router::get('/setcookie', function (){
	return Application::$APP->cookie->set('test', '1', time() + (86400 * 30), "/");
});
Router::get('/testabc' ,[TestController::class, 'dbTest']);
Router::get('/fakeUser', [TestController::class, 'fakeUser']);
Router::get('/fakePost', [TestController::class, 'fakePost']);
Router::redirect('/redirect', '/hello');
Router::get('/hello', function (){
	$_SESSION['user'] = 1;
	return 'hello world';
});
Router::request('/image',[TestController::class, 'imageProcessor']);
