<?php


namespace Controller;


use Model\Likes;
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

		$likes = new Likes();

		$liked = $likes->findOne([
						'user' => Application::$APP->user->getId(),
						'post' => $post->id,
						]);

		if ($liked->getId()) {
			if ($liked->status) {
				$liked->status = 0;
				$likes->type = intval(filter_var($_GET['react'], FILTER_VALIDATE_INT));
				if ($liked->update())
					echo "1";
			} else {
				$liked->status = 1;

				if ($liked->update())
					echo "0";
			}
		} else {
			$likes->post = $post->getId();
			$likes->user = Application::$APP->user->getId();
			$likes->status = 0;
			$likes->type = intval(filter_var($_GET['react'], FILTER_VALIDATE_INT));
			if ($likes->save())
				echo "1";
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
			$likes = New Likes();

			$postLikes = $likes->findAllBy(['post' => $id, 'status' => 0]);
			/** create a user model to fetch users */
			$users = New User();

			/** fetch first 5 users */
			$i = 0;
			while ($i < count($postLikes) && $i < 5) {
				$user = $users->getOneBy($postLikes[$i]['user']);
				array_push($usersLikes, [
					'user' => $user->name,
					'picture' => '/uploads/dps/default.jpg',
					'react' => $postLikes[$i]['type']
				]);
				$i++;
			}

			return json_encode($usersLikes);
		}

		throw New ExpiredException();
	}
}
