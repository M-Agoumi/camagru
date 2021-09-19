<?php


namespace controller;


use core\Application;
use core\Request;
use Middlewares\AuthMiddleware;
use models\Post;

class CameraController extends Controller
{
	public function __construct()
    {
        $this->registerMiddleware(New AuthMiddleware([]));
    }

	public function index()
	{
		return $this->render('pages/camera', ['title' => 'Camera']);
	}

	public function save(Request $request)
	{
		$post = New Post();
		$imgCode = Application::$APP->request->getBody()['picture'];
		$data = $imgCode;

		list($type, $data) = explode(';', $data);
		list(, $data)      = explode(',', $data);
		$data = base64_decode($data);
		$image = uniqid();
		$path = Application::$ROOT_DIR .'/runtime/tmp/image_' . $image .'.jpeg';
		if (file_put_contents($path, $data))
			$post->picture = $image;
		Application::$APP->session->set('picture', $path);
		Application::$APP->session->set('pictureData', $imgCode);

		return $this->render('pages/cameraShare', ['post'=> $post]);
	}

	public function share(Request $request)
	{
		$post = New Post();

		$post->loadData($request->getBody());

		/** get image from tmp to our uploads */
		$picture = Application::$ROOT_DIR .'/runtime/tmp/image_' . $post->picture .'.jpeg';
		if (!rename($picture, 'uploads/image_' . $post->picture . '.jpeg'))
			die("error while saving your image"); /** todo handle this */

		/** update picture name to match its url */
		$post->picture = 'image_' . $post->picture . '.jpeg';

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
