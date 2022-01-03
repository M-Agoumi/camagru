<?php


namespace Controller;


use Model\Comments;
use Model\Post;
use Simfa\Action\Controller;
use Simfa\Framework\Application;
use Simfa\Framework\Request;
use Simfa\Model\Preferences;

class PostCommentController extends Controller
{

	/** add a comment on a post method
	 *
	 * @param Post $post
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
		if (Application::isGuest())
			return "-2";

		$postTmp = $post;

		/** proceed if post exists */
		if ($postTmp) {
			/** load form */
			$comment = New Comments();
			$comment->loadData($request->getBody());

			/** check for validation */
			if (empty($comment->errors) && !empty($comment->content)) {
				/** fill all fields */
				$comment->post = $postTmp->id;
				$comment->user = Application::$APP->user->getId();

				/** get author preferences to check if he has enabled getting notifications by email */
				$preferences = new Preferences();
				$preferences->getOneBy('user', $post->author->id);

				if ($comment->save()) {
					if ($preferences->commentsMail != '0') {
						/** fill email data */
						$authorEmail = $post->author->email;
						$emailSubject = Application::$APP->user->username . " commented on your post";
						$emailContent = ['postComment', [
							'name' => Application::$APP->user->username,
							'postUrl' => Application::getEnvValue('appProtocol') . Application::getEnvValue('appURL') . '/post/' . $postTmp->slug
						]];
						$fromEmail = 'notification@camagru.io';

						$this->mail($authorEmail, $emailSubject, $emailContent, $fromEmail);
					}

					return "1";
				} else
					return "2";
			} else
				return "0";

		}

		return "-1";
	}
}
