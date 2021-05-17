<?php


namespace controller;


use core\Application;
use core\Exception\ForbiddenException;
use core\Exception\NotFoundException;

use core\Request;
use models\Likes;
use models\Post;
use models\User;

class PostController extends Controller
{
	/**
	 * @throws NotFoundException
	 */
	public function show($slug)
	{
		$post = New Post();

		
		$postc = $post->getOneBy('slug', $slug, 0);
		if ($postc) {
			$post->loadData($postc);
			return $this->render('pages/posts/show', ['post' => $post], ['title' => $post->title]);
		}

		throw New NotFoundException();
	}

	public function like(string $id, Request $request): string
	{
		if ($request->isPost()) {
			if (Application::isGuest())
				return "-1";

			$post = New Post();
			$post = $post->getOneBy($id);

			if ($post) {
				$likes = New Likes();
				$liked = $likes->findOne([
					'user' => Application::$APP->user->getId(),
					'post' => $post->id,
					]);

				if ($liked) {

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
					$likes->post = $id;
					$likes->user = Application::$APP->user->getId();
					$likes->status = 0;
					$likes->type = intval(filter_var($_GET['react'], FILTER_VALIDATE_INT));
					if ($likes->save())
						echo "1";
				}
			} else
				echo "-2";


			return "\n";
		}
		throw New ForbiddenException();
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

		throw New ForbiddenException();
	}
}