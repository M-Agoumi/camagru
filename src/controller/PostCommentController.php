<?php


namespace controller;


use core\Application;
use core\Exception\ForbiddenException;
use core\Request;
use models\Comments;
use models\Post;

class PostCommentController extends Controller
{

	/** add a comment on a post method
	 *
	 * @param string $slug
	 * @param Request $request
	 * @return string
	 * -2 => user is not authenticated
	 * -1 => post not found
	 * 0 => comment not valid
	 * 1 => comment added
	 * 2 => error occurred while saving the comment
	 */
	public function add(Post $post, Request $request):string
	{
		if (!$request->isPost())
			throw New ForbiddenException();
		if (Application::isGuest()) {
			return "-2";
		}

		$postc = $post;

		/** proceed if post exists */
		if ($postc) {
			/** load form */
			$comment = New Comments();
			$comment->loadData($request->getBody());

			/** check for validation */
			if (empty($comment->errors) && !empty($comment->content)) {
				/** fill all fields */
				$comment->post = $postc->id;
				$comment->user = Application::$APP->user->getId();
				if ($comment->save())
					return "1";
				else
					return "2";
			} else
				return "0";

		}

		return "-1";
	}
}
