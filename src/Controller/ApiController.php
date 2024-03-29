<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   ApiController.php                                :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/10/18 17:47:14 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/10/18 17:47:14 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

namespace Controller;


use Middlewares\AuthMiddleware;
use Model\Cover;
use Model\Post;
use Simfa\Action\Controller;

class ApiController extends Controller
{

	public function __construct()
	{
		$this->registerMiddleware(new AuthMiddleware(['covers']));
	}

	/**
	 * @param Post $post
	 * @return false|string
	 */
   public function posts(Post $post): bool|string
   {
	   $posts = $post->paginate([
		   'order' => 'DESC',
		   'articles' => 20,
		   'autoPage' => true
	   ],['author', 'comment', 'created_at', 'status', 'updated_at']);

	   return $this->json($posts);
   }

	/**
	 * @param Cover $cover
	 * @return bool|string
	 */
   public function covers(Cover $cover): bool|string
   {
	   $query = $cover->queryBuilder();
	   $collection = $query->select('name, image')->get();

		return $this->json($collection);
   }

	/**
	 * @param array|null $posts
	 * @param Post $post
	 * @return array|null
	 */
   private function generateHashtags(?array $posts, Post $post): ?array
   {
	   for ($i = 0; $i < count($posts); $i++)
	   {
		   $posts[$i]['hashtags'] = $post->hashtag($posts[$i]['comment']);
	   }

	   return $posts;
   }
}
