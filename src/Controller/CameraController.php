<?php


namespace Controller;




use Middlewares\AuthMiddleware;
use Model\Emote;
use Model\Post;
use Simfa\Action\Controller;
use Simfa\Framework\Application;
use Simfa\Framework\Request;

class CameraController extends Controller
{
	public function __construct()
    {
        $this->registerMiddleware(New AuthMiddleware([]));
    }

	public function index()
	{
		$emotes = New Emote();
		$emotes = $emotes->findAll();

		return $this->render('pages/camera', ['title' => 'Camera', 'emotes' => $emotes]);
	}

	public function save()
	{
		$post = New Post();
		$imgCode = Application::$APP->request->getBody()['picture'];
		$emotesPost = $_POST['emote'] ?? [];
		$emotes = []; // processed input
		foreach ($emotesPost as $key => $emote) {
			$coordination = explode('/', $emote);
			$emotes[$key] = [
				'y' => 	intval($coordination[0] ?? 0),
				'x' => intval($coordination[1] ?? 0),
				'z' => intval($coordination[2] ?? 0)
			];
		}
		// sort by z-index
		uasort($emotes, function($a, $b) {
			return $a['z'] <=> $b['z'];
		});
		$image = $this->mergeEmotes($imgCode, $emotes);
		$imageFileName = 'image_' . uniqid() . '.png';
		$imageWrapper = fopen(Application::$ROOT_DIR . '/public/tmp/' . $imageFileName , "w") or die("Can't create file");
		imagepng($image, $imageWrapper);
		$post->picture = $imageFileName;

		return $this->render('pages/cameraShare', ['post'=> $post, 'title' => 'share to the world']);
	}

	private function mergeEmotes($image, $emotes)
	{
		$image = imagecreatefromjpeg($image);
		// start merging images
		$emoteEntity = new Emote();
		foreach ($emotes as $key => $emote) {
			$emoteEntity->getOneBy('name', $key);
			if ($emoteEntity->getId()) {
				$stamp = imagecreatefrompng(Application::$ROOT_DIR . '/public/assets/img/' . $emoteEntity->getFile());
				imagecopy($image, $stamp, $emote['x'], $emote['y'], 0, 0, imagesx($stamp), imagesy($stamp));
			}
		}

		return $image;
	}

	private function resize_image($image, int $newWidth, int $newHeight) {
		$newImg = imagecreatetruecolor($newWidth, $newHeight);
		imagealphablending($newImg, false);
		imagesavealpha($newImg, true);
		$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
		imagefilledrectangle($newImg, 0, 0, $newWidth, $newHeight, $transparent);
		$src_w = imagesx($image);
		$src_h = imagesy($image);
		imagecopyresampled($newImg, $image, 0, 0, 0, 0, $newWidth, $newHeight, $src_w, $src_h);
		return $newImg;
	}

	public function share(Request $request)
	{
		$post = New Post();

		$post->loadData($request->getBody());

		/** get image from tmp to our uploads */
		$picture = Application::$ROOT_DIR .'/public/tmp/' . $post->picture;
		if (!rename($picture, 'uploads/' . $post->picture))
			die("error while saving your image");

		/** generate slug */
		if ($post->title)
			$post->slug = $this->slugify($post->title) . '-' . uniqid();
		elseif ($post->comment)
			$post->slug = str_replace(' ', '-', substr($post->comment, 0, 10));
		else
			$post->slug = str_replace('.', '-', uniqid('post-', true));

		$post->author = Application::$APP->user;

		$post->status = 0;

		if ($post->validate() && $post->save())
			return Application::$APP->response->redirect('/post/' . $post->slug);

		return "Ops";
	}
}
