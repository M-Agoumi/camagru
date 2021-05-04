<?php


namespace controller;


use core\Application;
use core\Exception\ForbiddenException;
use core\Exception\NotFoundException;

use core\Request;
use models\Likes;
use models\Post;

class PostController extends Controller
{
	/**
	 * @throws NotFoundException
	 */
	public function show($slug)
	{
		$post = New Post();

		$post = $post->getOneBy('slug', $slug);
		if ($post)
			return $this->render('pages/posts/show', ['post' => $post], ['title' => $post->title]);
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
					]
				);
				if ($liked) {
					if ($liked->status) {
						$liked->status = 0;
						if ($liked->update())
							echo "0";
					} else {
						$liked->status = 1;
						if ($liked->update())
							echo "1";
					}
				} else {
					$likes->post = $id;
					$likes->user = Application::$APP->user->getId();
					$likes->status = 0;
					$likes->type = 0;
					if ($likes->save())
						echo "1";
				}
			} else
				echo "Post not found maybe got deleted";


			return "\n";
		}
		throw New ForbiddenException();
	}
}