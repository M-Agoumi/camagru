<?php

namespace controller;

use core\Application;
use core\Request;
use Middlewares\DevMiddleware;
use models\Client;
use models\Post;
use models\User;
use vendor\FakeData\FakeDataFactory;

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
		Application::$APP->controller->layout = 'auth';

		return render('test');
	}

	public function mailTest(): bool
	{
		return ($this->mail('agoumihunter@gmail.com', 'testing', ['test', ['receiver' => 'Agoumi']]));
	}

	public function autoWire(User $user)
	{
		return $user;
	}

	public function autoFetch(User $user)
	{
		var_dump($user);
	}

	public function phpinfo()
	{
		var_dump(phpinfo());
	}

	/** a security breach to update password to any account cause im done with resetting my password everyday :)
	 * todo remove this method
	 * @param Request $request
	 * @return false|string|string[]
	 */
	public function password(Request $request, User $user)
	{
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

	/**
	 * ongoing..
	 */
	public function pagination(): string
	{
		$posts = new Post();

		$paginatedPosts = $posts->paginate(['articles' => 5]);
		echo "<pre>";
		var_dump($posts, $paginatedPosts);
		return '';
	}

	public function viewEngine()
	{
		return render('dev/engine_test', ['test' => 3]);
	}

	public function emailView()
	{
		return render('mails/restorePassword', ['port' => 8000, 'token' => 'test123123123123']);
	}

	public function dbTest()
	{
		$user = new Client();

		$user->setUsername('test');
		$user->getOneBy(1);

		var_dump($user);
		die($user->getUsername());

		return $user;
	}

	/**
	 * @return string
	 */
	public function fakeUser()
	{
		$fake = FakeDataFactory::create();

		echo <<<html
		<table>
			<thead>
				<tr>
				    <th>name</th>
				    <th>username</th>
				    <th>email</th>
				    <th>password</th>
				  </tr>
			</thead>
			<tbody>
		html;

		/**
		 * generate user
		 */

		for ($i = 0; $i < 50; $i++) {
			$user = new User();
			$user->name = $fake->name;
			$user->username = $fake->username($user->name);
			$user->email = $fake->email($user->name);
			$user->password = "P@ssw0rd!";
//			$user->save();
			echo "<tr>";
				echo '<td>' . $user->getName() . '</td>';
				echo '<td>' . $user->getUsername() . '</td>';
				echo '<td>' . $user->getEmail() . '</td>';
				echo '<td>' . $user->getPassword() . '</td>';
			echo "</tr>";

		}
		return '';
	}

	public function fakePost(User $user): void
	{
		$fake = FakeDataFactory::create();

		echo <<<html
		<table>
			<thead>
				<tr>
				    <th>title</th>
				    <th>comment</th>
				    <th>picture</th>
				    <th>slug</th>
				    <th>author</th>
				  </tr>
			</thead>
			<tbody>
		html;

		/**
		 * generate post
		 */
		$user->getOneBy(1);
		for ($i = 0; $i < 100; $i++) {
			$post = new Post();

			$post->title = $fake->sentence;
			$hashtag = str_replace('#', '', $fake->hashtag(1));
			$post->comment = $fake->text(5, 30) . ' #' . $hashtag . ' ' . $fake->hashtag(2);
			$post->picture = $fake->picture(Application::$ROOT_DIR . '/public/uploads/', $hashtag);
			$post->slug = $fake->slugify($post->title);
			$post->author = $user;
			$post->status = 0;

			$post->save();
		}

		echo "done";
	}

}
