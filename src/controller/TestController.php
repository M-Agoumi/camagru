<?php


namespace controller;

use core\Application;
use core\Request;
use core\View;
use Middlewares\DevMiddleware;
use models\core\UserToken;
use models\Post;
use models\Roles;
use models\User;

class TestController extends Controller
{
	public function __construct()
	{
		$this->registerMiddleware(New DevMiddleware([]));
	}

	public function linkVar($var = 'test')
	{
		return $var;
	}

	public function imageCanvas(User $user)
	{
		return render('test', ['user' => $user]);
	}

	public function mailTest(): bool
	{
		return ($this->mailer('agoumihunter@gmail.com', 'testing', 'this is a huge fat test'));
	}

	public function autoWire(): ?Post
	{
		$post = new Post();

		$post->getOneBy(1);
		return $post;
	}

	public function autoFetch(User $user)
	{
		var_dump($user);
	}

	public function phpinfo()
	{
		return Application::$APP->view->renderContent(phpinfo());
	}

	/** a security breach to update password to any account cause im done with resetting my password everyday :)
	 * todo remove this method
	 * @param Request $request
	 * @return false|string|string[]
	 */
	public function password(Request $request)
	{
		$user = new User();

		if ($request->isPost()) {
			$user->loadData($request->getBody());

			$password = $user->password;

			$updatedUser = $user->getOneBy('email', $user->email, 0);

			$user->loadData((array)$updatedUser);

			$user->password = $password;
			$user->pass = true;

			if ($user->update())
				return 'done';

			return 'something went wrong';
		}

		return render('pages/dev/resetPassword', ['user' => $user]);
	}

	public function cookie()
	{
//		$userToken = New UserToken();
//
//		$userToken->setUser(2);
//
//		$token = openssl_random_pseudo_bytes(64);
//		//Convert the binary data into hexadecimal representation.
//		$userToken->token = bin2hex($token);
//		$userToken->used = 0;
//		echo 'one<br>';
//		$userToken->save();
//		echo 'two<br>';

		return Application::$APP->view->renderContent('test', ['title' => 'test'], );
	}
}
