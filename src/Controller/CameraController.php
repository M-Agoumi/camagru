<?php


namespace Controller;




use Exception;
use GdImage;
use Middlewares\AuthMiddleware;
use Model\Emote;
use Model\Post;
use Simfa\Action\Controller;
use Simfa\Framework\Application;
use Simfa\Framework\Request;
use Simfa\Framework\Session;

class CameraController extends Controller
{
	public function __construct()
    {
        $this->registerMiddleware(New AuthMiddleware([]));
    }

	/**
	 * @return string
	 */
	public function index(): string
	{
		$emotes = New Emote();
		$emotes = $emotes->findAll();

		return $this->render('pages.camera.camera', ['title' => 'Camera', 'emotes' => $emotes]);
	}

	/**
	 * @return string|void
	 * @throws Exception
	 */
	public function save(Session $session)
	{
		$post = New Post();
		$post->picture = $session->get('post-tmp-image');

		return $this->render('pages.test.cameraShare', ['post'=> $post, 'title' => 'share to the world']);
	}


	/**
	 * @param Request $request
	 * @return string|void
	 * @throws Exception
	 */
	public function share(Request $request, Session $session)
	{
		$post = New Post();

		$post->loadData($request->getBody());

		/** get image from tmp to our uploads */
		$picture = Application::$ROOT_DIR .'/public/tmp/' . $post->picture;
		if (!rename($picture, 'uploads/post/' . $post->picture))
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

		$session->unset('post-tmp-image');
		if ($post->validate() && $post->save())
			return Application::$APP->response->redirect('/post/' . $post->slug);

		return "Ops";
	}

	public function dismiss()
	{
		$image = Application::$APP->session->get('post-tmp-image');
		if (file_exists(Application::$ROOT_DIR . '/runtime/tmp/' . $image))
			unlink(Application::$ROOT_DIR . '/runtime/tmp/' . $image);
		Application::$APP->session->unset('post-tmp-image');
	}
}
