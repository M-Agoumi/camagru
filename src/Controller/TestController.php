<?php

namespace Controller;


use Helper\TimeHelper;
use Middlewares\DevMiddleware;
use Model\Post;
use Model\User;
use Simfa\Action\Controller;
use Simfa\Framework\Application;
use Simfa\Framework\Helper;
use Simfa\Framework\Request;
use FakeData\FakeDataFactory;

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
	 * @param User $user
	 * @return string
	 * @throws \Exception
	 */
	public function password(Request $request, User $user): string
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

	/**
	 * @return string|null
	 */
	public function viewEngine(): ?string
	{
		return render('dev/engine_test', ['test' => 3]);
	}

	public function emailView(): ?string
	{
		return render('mails/restorePassword', ['port' => 80, 'token' => 'test123123123123']);
	}

	/**
	 * @return string
	 */
	public function fakeUser(): string
	{
		$fake = FakeDataFactory::create();

		echo <<<html
		<table style="border: 2px solid #fbb034">
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
			$user->save();
			echo "<tr>";
				echo '<td>' . $user->getName() . '</td>';
				echo '<td>' . $user->getUsername() . '</td>';
				echo '<td>' . $user->getEmail() . '</td>';
				echo '<td>' . $user->getPassword() . '</td>';
			echo "</tr>";

		}

		return 'void';
	}

	public function fakePost()
	{
		$fake = FakeDataFactory::create();

		echo <<<html
		<style>
			table, th, td {
			  border: 1px solid black;
			  border-collapse: collapse;
		}
		</style>
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
		for ($i = 0; $i < 100; $i++) {
			$post = new Post();

			$post->title = $fake->sentence;
			$hashtag = str_replace('#', '', $fake->hashtag(1));
			$post->comment = $fake->text(5, 30) . ' #' . $hashtag . ' ' . $fake->hashtag(2);
			$post->picture = 'https://loremflickr.com/650/550/' . $hashtag;
			$post->slug = $fake->slugify($post->title);
			$post->author = User::findOne(['id' => $fake->model(User::class)]);
			$post->status = 0;
			echo '<tr>';
				echo '<td>[' . $i . '] ' . $post->title . '</td>';
				echo '<td>' . $post->comment . '</td>';
				echo '<td><img src="' . $post->picture . '"/></td>';
				echo '<td>' . $post->slug . '</td>';
				echo '<td>' . $post->author->getName() . '</td>';
			echo '</tr>';

			$post->save();
		}

		return "done";
	}

	public function imageProcessor()
	{
		// Load the stamp and the photo to apply the watermark to
		$stamp = \imagecreatefrompng(Application::$ROOT_DIR . '/public/tmp/source.png');
		$im = \imagecreatefromjpeg(Application::$ROOT_DIR . '/public/tmp/prototype.jpg');

		// Set the margins for the stamp and get the height/width of the stamp image
		$marge_right = $_GET['x'] ?? 10;
		$marge_bottom = $_GET['y'] ?? 10;

		// Copy the stamp image onto our photo using the margin offsets and the photo
		// width to calculate positioning of the stamp.
		imagecopy($im, $stamp, $marge_right, $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

		// Output and free memory
		header('Content-type: image/png');
		imagepng($im);
		imagedestroy($im);
	}

	public function testAutowired(Post $post, User $user): string
	{
		$output = '<pre>';
		$output .= print_r($post, 1);
		$output .= print_r($user, 1);

		return $output . '</pre>';
	}

	public function injector(): string
	{
		echo '<pre>';
		$helper = Helper::getHelper(TimeHelper::class);
		var_dump($helper);
		return '<br>done';
	}

}
