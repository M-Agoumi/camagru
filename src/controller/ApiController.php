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

namespace controller;

use models\Post;

class ApiController extends Controller
{

   public function __construct()
   {
        // TODO implement your controller
   }

   public function posts(Post $post)
   {
	   $posts = $post->paginate([
		   'order' => 'DESC',
		   'articles' => 3
	   ]);

	   $posts = $this->generateHashtags($posts, $post);

	   return $this->json($posts);
   }

   private function generateHashtags(?array $posts, Post $post): ?array
   {
	   for ($i = 0; $i < count($posts); $i++)
	   {
		   $posts[$i]['hashtags'] = $post->hashtag($posts[$i]['comment']);
	   }

	   return $posts;
   }
}
