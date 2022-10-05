<?php


namespace Controller;


use Model\Like;
use Model\Post;
use Model\User;
use Simfa\Action\Controller;
use Simfa\Framework\Application;
use Simfa\Framework\Db\QueryBuilder;
use Simfa\Framework\Exception\ExpiredException;
use Simfa\Framework\Exception\ForbiddenException;
use Simfa\Framework\Request;

class PostController extends Controller
{
	/**
	 * @param Post $post
	 * @return string
	 */
	public function show(Post $post): string
	{
		/** get likes */
		$likes = new Like();

		$likesCount = $likes->getCount(['post' => $post->entityID, 'status' => 0]);
		if (Application::$APP->user) {
			$liked = $likes->findOne(['post' => $post->entityID, 'user' => Application::$APP->user->getId(), 'status' => 0]);
			$liked = $liked->getId() ? $liked->getType() : -1;
		} else
			$liked = -1;

		if (!$post->getTitle())
			$post->setTitle($post->getAuthor()->getUsername() . ' post');

		$data = [
			'post'      => $post,
			'title'     => $post->title,
			'author'    => $post->author,
			'likesCount'=> $likesCount,
			'liked'     => $liked
		];

		return render('pages/posts/show', $data);
	}

	/**
	 * @param Post $post
	 * @param Request $request
	 * @return string
	 */
	public function like(Post $post, Request $request): string
	{
		if (Application::isGuest() || !$request->isPost())
			return "-1";

		$likes = new Like();
		$type = intval(filter_var($_GET['react'], FILTER_VALIDATE_INT));

		$liked = $likes->findOne([
						'user' => Application::$APP->user->getId(),
						'post' => $post->getId(),
						]);

		if ($liked->getId()) {
			if ($liked->getType() == $type) {
				if ($liked->delete(1)) {
					return $this->json(0);
				}
				return $this->json(10);
			} else {
				$liked->setType($type);
				if ($liked->update())
					return $this->json(2);
			}
		} else {
			$likes->post = $post->getId();
			$likes->user = Application::$APP->user->getId();
			$likes->status = 0;
			$likes->type = $type;
			if ($likes->save())
				return $this->json(1);
		}

		return "\n";
	}

	/**
	 * @param string $id
	 * @param Request $request
	 * @return false|string
	 * @throws ExpiredException
	 */
	public function showLikes(string $id, Request $request): bool|string
	{
		if ($request->isPost()) {
			/** check if the user is logged or not */
			if (Application::isGuest())
				return "-1";

			/** Users array */
			$usersLikes = [];

			/** create like model to fetch likes */
			$likes = New Like();

			$postLikes = $likes->findAllBy(['post' => $id, 'status' => 0]);
			/** create a user model to fetch users */
			$users = New User();

			/** fetch first 5 users */
			$i = 0;

			while ($i < count($postLikes) && $i < 5) {
				$users->getOneBy($postLikes[$i]['user']);
				$usersLikes[] = [
					'user' => $users->name,
					'picture' => '/uploads/dps/default.jpg',
					'react' => $postLikes[$i]['type']
				];
				$i++;
			}

			return json_encode($usersLikes);
		}

		throw New ExpiredException();
	}

	/**
	 * @param Post $post
	 * @return string
	 * @throws ForbiddenException
	 */
	public function delete(Post $post)
	{
		if (!isset($_GET[Application::$APP->session->getToken('post')]))
			throw new ForbiddenException();

		 if ($post->delete(1)) {
			Application::$APP->session->setFlash('success', 'Post Deleted Successfully');
			Application::$APP->response->redirect('/');
		 }

		return "Deleted :)"; 
	}

	/**
	 * @param $hashtag
	 * @return string
	 */
	public function hashtag($hashtag): string
	{
		return $this->render('pages.posts.hashtag',['title' => $hashtag. ' - Camagru', 'hashtag' => $hashtag]);
	}

	/**
	 * @param $hashtag
	 * @return bool|string
	 */
	public function hashtagPost($hashtag): bool|string
	{
		$post = new Post();

		$query = $post->queryBuilder();
		$posts = $query->select('*')->where('comment', 'like', '#' . $hashtag, '%')->
			limit(10)->offset(intval($_POST['page'] * 10 ?? 0))->desc()->get();

		return $this->json($posts);
	}
}
