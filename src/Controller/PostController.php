<?php


namespace Controller;


use Model\Like;
use Model\Post;
use Model\User;
use Simfa\Action\Controller;
use Simfa\Framework\Application;
use Simfa\Framework\Exception\ExpiredException;
use Simfa\Framework\Request;

class PostController extends Controller
{
	/**
	 * @param Post $post
	 * @return false|string|string[]
	 */
	public function show(Post $post)
	{
		return render('pages/posts/show', ['post' => $post, 'title' => $post->title]);
	}

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
				if ($liked->delete())
					return $this->json(0);
			} else {
				$liked->setType($type);
				if ($liked->update())
					return $this->json(1);
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

	public function showLikes(string $id, Request $request)
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
}
