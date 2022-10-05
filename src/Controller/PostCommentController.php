<?php


namespace Controller;


use Model\Comments;
use Model\Post;
use Simfa\Action\Controller;
use Simfa\Framework\Application;
use Simfa\Framework\Request;
use Simfa\Model\Preference;

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
	 * @throws \Exception
	 */
	public function add(Post $post, Request $request):string
	{
		if (Application::isGuest())
			return $this->json(['code' => -2]);

		$postTmp = $post;

		/** load form */
		$comment = New Comments();
		try {
			$comment->loadData($request->getBody());
		} catch (\Exception $e) {
			return $this->json(['code' => -3, 'token' => Application::$APP->session->getCsrf()]);
		}

		/** check for validation */
		if (!empty($comment->content)) {
			/** fill all fields */
			$comment->post = $postTmp->entityID;
			$comment->user = Application::$APP->user->getId();

			/** get author preferences to check if he has enabled getting notifications by email */
			$preferences = new Preference();
			$preferences->getOneBy('user', $post->author->entityID);

			if ($comment->save()) {
				if (is_null($preferences->getId()) || $preferences->mail != '0') {
					/** fill email data */
					$authorEmail = $post->getAuthor()->getEmail();
					error_log("email: " . print_r($post->getAuthor(), 1)."\n", 3, "/var/www/camagru/runtime/logs/logs.log");
					$emailSubject = Application::$APP->user->getUsername() . " commented on your post";
					$emailContent = ['postComment', [
						'name' => Application::$APP->user->getUsername(),
						'postUrl' => Application::getEnvValue('APP_PROTOCOL') . Application::getEnvValue('APP_URL') . '/post/' . $postTmp->slug
					]];
					$fromEmail = 'notification@camagru.io';

					$this->mail($authorEmail, $emailSubject, $emailContent, $fromEmail);
				}

				return $this->json(['code' => 1, 'token' => Application::$APP->session->getCsrf()]);
			} else
				return $this->json(['code' => 2, 'token' => Application::$APP->session->getCsrf()]);
		} else
			return $this->json(['code' => 0, 'token' => Application::$APP->session->getCsrf()]);
	}
}
