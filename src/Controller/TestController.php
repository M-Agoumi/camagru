<?php

namespace Controller;


use Exception;
use Helper\TimeHelper;
use Middlewares\DevMiddleware;
use Model\Background;
use Model\Config;
use Model\Cover;
use Model\Emote;
use Model\Post;
use Model\Template;
use Model\User;
use Service\ImageProcessor;
use Simfa\Action\Controller;
use Simfa\Framework\Application;
use Simfa\Framework\Helper;
use Simfa\Framework\Request;
use FakeData\FakeDataFactory;
use Simfa\Framework\Session;

class TestController extends Controller
{
	public function __construct()
	{
		$this->registerMiddleware(new DevMiddleware([]));
	}

	public function linkVar($var = 'test')
	{
		return $var;
	}

	/**
	 * @return string|null
	 */
	public function imageCanvas(): ?string
	{
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

	/** a security breach to update password to any account cause im done with resetting my password every day :)
	 * @param Request $request
	 * @param User $user
	 * @return string
	 * @throws Exception
	 */
	public function password(Request $request, User $user): string
	{
		if ($request->isPost()) {
			$user->loadData($request->getBody());

			$password = $user->password;

			$updatedUser = $user->getOneBy('username', $user->getUsername(), 0);

			if ($updatedUser) {
				$user->loadData((array)$updatedUser);
				$user->setPassword($password);
				$user->setPass(true);

				if ($user->update())
					return 'done';
				return 'something went wrong';
			}
			return 'user not found';
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

	public function cover()
	{
		/** get user cover image */
		$user = new User();
		$user->getOneBy(22);
		$bg = new Background();
		$bg->getOneBy('user', $user->getId());
		if (!$bg->getId()) {
			$config = new Config();
			$config->getOneBy('name', 'user/profile/cover');
			$image = $config->getValue();
		} else {
			if (!$bg->getType())
				$image = $bg->getImage();
			else {
				$cover = new Cover();
				$cover->getOneBy($bg->getImage());
				$image = $cover->getImage();
			}
		}

		return $this->render('dev.testCover', [
			'user' => $user,
			'cover' => $image,
			'bg' => $bg,
			'covers' => (new Cover())->findAll()
		]);
	}

	/** new camera system */
	public function camera(): ?string
	{
		$emote = new Emote();
		$records = $emote->findAll();
		$emotes = array_filter($records, fn ($entry) => !$entry['type']);
		$borders = array_filter($records, fn ($entry) => $entry['type']);

		$template = new Template();
		$templates = $template->findAllBy(['user' => '21']);

		return render('pages.test.camera', [
			'title' => 'camera',
			'emotes' => $emotes,
			'borders' => $borders,
			'templates' => $templates
		]);
	}

	/**
	 * @return string
	 * @todo verify the CSRF header
	 * @todo add to authenticated middleware for this method
	 */
	public function animated(): string
	{
		if (isset($_FILES['file']['name'])) {
			// file name

			$filename = $_FILES['file']['name'];
			$file_size = $_FILES['file']['size'];

			$location = 'tmp/' . $filename;

			// file extension
			$file_extension = pathinfo($location, PATHINFO_EXTENSION);
			$file_extension = strtolower($file_extension);

			if ($file_extension == 'gif') {
				if ($file_size < Application::getAppConfig('post', 'max_file_size')) {
					// Upload file
					if (@move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
						Application::$APP->session->set('post-tmp-image', $filename);
						return $this->json(['status' => true, 'message' => 'the image has been uploaded']);
					} else
						return $this->json(['status' => false, 'message' => 'something went wrong']);
				}
				return $this->json(['status' => false, 'message' => 'file too big: ' . $file_size]);
			}
		}

		return "\n";
	}

	/**
	 * @param Session $session
	 * @return string
	 * @todo user must be authenticated in this method too
	 */
	public function saveAnimated(Session $session): string
	{
		if (!$session->get('post-tmp-image'))
			redirect('/');

		$post = new Post();

		$post->setPicture($session->get('post-tmp-image'));
		$session->unset('post-tmp-image');

		return $this->render('pages.test.cameraShare', ['post' => $post, 'title' => 'share to the world']);
	}

	/**
	 * @param Session $session
	 * @return string
	 * @todo csrf check | Auth middleware
	 */
	public function savePicture(Session $session): string
	{
		if (isset($_FILES['file']['name'])) {
			// file name
			$filename = $_FILES['file']['name'];
			$file_size = $_FILES['file']['size'];

			$new_name = uniqid() . $filename;
			$location = 'tmp/' . $new_name;

			// file extension
			$file_extension = pathinfo($location, PATHINFO_EXTENSION);
			$file_extension = strtolower($file_extension);

			if (in_array($file_extension, Application::getAppConfig('post', 'allowed_ext'))) {
				if ($file_size < Application::getAppConfig('post', 'max_file_size')) {
					// Upload file
					if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
						$session->set('post-tmp-image', $new_name);
						return $this->json([
							'status' => true,
							'message' => 'the image has been uploaded',
							'picture' => $new_name
						]);
					} else {
						return $this->json([
							'status' => false,
							'message' => 'something went wrong',
							'picture' => null
						]);
					}
				}
				return $this->json(['status' => false, 'message' => 'file too big: ' . $file_size]);
			}
		}

		return "\n";
	}

	public function saveCamera(Request $request, Session $session)
	{
		$filename = uniqid() . '-camera.jpg';
		file_put_contents('tmp/' . $filename, file_get_contents($request->getBody()['file']));

		$session->set('post-tmp-image', $filename);
		return $this->json([
			'status' => true,
			'message' => 'the image has been taken',
			'picture' => $filename
		]);
	}

	/**
	 * @throws Exception
	 */
	public function processImage(Session $session): bool|string
	{
		$data = json_decode($_POST['data']);

		$template = new Template();
		$template->setUser(Application::$APP->user);
		$template->setContent(json_encode($data->data));

		$picture = $data->picture;
		$data = $data->data;
		$image_file = Application::$ROOT_DIR . '/public/tmp/' . $picture;
		$imageProcessor = ImageProcessor::getInstance();

		if (!file_exists($image_file))
			return $this->json(['status' => false, 'message' => 'uploaded image not found']);

		$image = $imageProcessor->prepareMainImage($image_file, $data->width, $data->height);

		$imageCode = match(pathinfo($image, PATHINFO_EXTENSION)) {
			'jpg', 'jpeg' => imagecreatefromjpeg($image),
			'png' => imagecreatefrompng($image),
			'gif' => imagecreatefromgif($image),
			default => throw new Exception('Unknown image type'),
		};

		if ($imageCode) {
			if (count($data->emotes)) {
				$this->mergeEmote($data, $imageCode, $imageProcessor);
			}
			if ($data->border) {
				$newImage = $this->mergeBorder($data, $imageCode, $imageProcessor, $picture);
				unlink($image);
				$session->set('post-tmp-image', $newImage);
			}
		}

		$templateExists = $template->queryBuilder()->select()->where('user', 'like', $template->getUser()->getId())
			->and()->where('content', 'like', $template->getContent())->get();

		if (!count($templateExists))
			$template->save();

		return $this->json(['status' => true]);
	}

	/**
	 * @param $data
	 * @param $imageCode
	 * @param ImageProcessor $imageProcessor
	 * @param string $imageName
	 * @return string
	 * @throws Exception
	 */
	private function mergeBorder($data, $imageCode, ImageProcessor $imageProcessor, string $imageName): string
	{
		/** use database */
		$border_image = $data->border->border;
		$border_file = $imageProcessor->prepareBorder($border_image, $data->width, $data->height);

		if (!file_exists($border_file)) {
			return $this->json(['status' => false, 'message' => 'border source image not found']);
		}

		$borderCode = imagecreatefrompng($border_file);
		imagealphablending($imageCode, true);
		imagesavealpha($imageCode, true);

		imagecopy($imageCode, $borderCode, 0, 0, 0, 0, $data->width, $data->height);

		$newImageName =  uniqid() . '_' . $imageName;
		$newImagePath = Application::$ROOT_DIR . '/public/tmp/' . $newImageName;
		$imageWrapper = fopen($newImagePath, "w") or throw new Exception('failed to open cache file for writing');
		imagepng($imageCode, $imageWrapper);

		imagedestroy($imageCode);
		imagedestroy($borderCode);

		return $newImageName;
	}

	/**
	 * @param $data
	 * @param $imageCode
	 * @param ImageProcessor $imageProcessor
	 * @return void
	 * @throws Exception
	 */
	private function mergeEmote($data, $imageCode, ImageProcessor $imageProcessor): void
	{
		/** check if we print even disabled emotes [emoteData->active == false] */
		/** use database */
		foreach ($data->emotes as $emoteData) {
			if (!$emoteData->active)
				continue ;
			$emoteImage = $imageProcessor->prepareEmote($emoteData->picture, $emoteData->width, $emoteData->height);


			if (!file_exists($emoteImage)) {
				$this->json(['status' => false, 'message' => 'border source image not found']);
				return;
			}

			$borderCode = imagecreatefrompng($emoteImage);
			imagealphablending($imageCode, true);
			imagesavealpha($imageCode, true);

			imagecopy($imageCode, $borderCode, intval($emoteData->left), intval($emoteData->top), 0, 0, $emoteData->width, $emoteData->height);

		}
		$imageWrapper = fopen(Application::$ROOT_DIR . '/public/tmp/agoumiTesting.png', "w") or throw new Exception('failed to open cache file for writing');
		imagepng($imageCode, $imageWrapper);

		imagedestroy($imageCode);
		if (isset($borderCode))
			imagedestroy($borderCode);
	}

	public function continueMessage()
	{
		print_r(route('camera.share'));
		echo '<br>';
		print_r(Application::$APP->request->getPath());
	}
}
