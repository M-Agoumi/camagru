<?php


namespace controller;


use core\Exception\NotFoundException;
use models\Post;

class PostController extends Controller
{
	public function show($slug)
	{
		$post = New Post();

		$post = $post->getOneBy('slug', $slug);
		if ($post)
			return "<img src='/uploads/$post->picture'>";
		throw New NotFoundException();
	}
}