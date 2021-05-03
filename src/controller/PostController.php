<?php


namespace controller;


use core\Exception\ForbiddenException;
use core\Exception\NotFoundException;

use core\Request;
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

	public function like(string $slug, Request $request): string
	{
		if ($request->isPost())
			return "liking $slug";
		throw New ForbiddenException();
	}
}