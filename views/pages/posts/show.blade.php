<?php

use core\Application;
use models\Comments;

/** @var $post \models\Post */
?>
<div class="center">
    <h1 class="usernameTitle"><?=$post->title?> <sub><small><em><?=$post->updated_at ? '(Edited)' : ''?></em></small></sub></h1>
    <img src="/uploads/<?=$post->picture?>" alt="<?=$post->comment ?? $post->title?>">
    <div class="usernameInfo">
		<p><?=$post->highlightHashtag($post->comment)?></p>
		<?php
		/** todo place this one somewhere better :D */
		function humanTiming($time): string
		{

			$time = time() - $time; // to get the time since that moment
			$time = ($time < 1) ? 1 : $time;
			$tokens = array(
				31536000 => 'year',
				2592000 => 'month',
				604800 => 'week',
				86400 => 'day',
				3600 => 'hour',
				60 => 'minute',
				1 => 'second'
			);

			foreach ($tokens as $unit => $text) {
				if ($time < $unit) continue;
				$numberOfUnits = floor($time / $unit);
				return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
			}
			return "0";
		}

		/** get author name */
		$author = New \models\User();
		$author = $author->getOneBy($post->author);
		if ($author)
			$author = $author->name;
		else
			$author = 'Anonymous';

		/** get likes */
		$likes = New \models\Likes();

	$likesCount = $likes->getCount(['post' => $post->id, 'status' => 0]);
	if (Application::$APP->user)
		$liked = $likes->getCount(['post' => $post->id, 'user' => Application::$APP->user->getId(), 'status' => 0]);
	else
		$liked = 0;

		//    echo $post->author;
		?>
		<p>posted <?=humanTiming(strtotime($post->created_at))?> ago by <span class="authorName"><?=$author?></span></p>
	</div>
    <div class="filters">
    <span>
        <span>
            (<span onclick="showLikes(<?=$post->id?>)"><?=$likesCount?></span>)
        </span>
        <span>
            <span onclick="likePost(<?=$post->id?>, this, 0)"><img class="react" src="/uploads/reactions/like.png"/></span>
            <span onclick="likePost(<?=$post->id?>, this, 1)"><img class="react" src="/uploads/reactions/heart.png"/></span>
            <span onclick="likePost(<?=$post->id?>, this, 5)"><img class="react" src="/uploads/reactions/wow.png"/></span>
            <span onclick="likePost(<?=$post->id?>, this, 2)"><img class="react" src="/uploads/reactions/haha.png"/></span>
            <span onclick="likePost(<?=$post->id?>, this, 3)"><img class="react" src="/uploads/reactions/sad.png"/></span>
            <span onclick="likePost(<?=$post->id?>, this, 4)"><img class="react" src="/uploads/reactions/angry.png"/></span>
        </span>
<?php
	    if (Application::$APP->user && Application::$APP->user->id == $post->author):
	    ?>
        <span>edit post</span>
<?php endif; ?>
	    <?php //var_dump($post)?>
    </span>
        <div>
            <h2>Comments</h2>
            <div>
				<?php
				$comment = New Comments();
				$form = \core\Form\Form::begin(
					'/api/post/comment/' . $post->slug, 'POST', '',
					'onsubmit="return addComment(event, \'' . $post->slug . '\')" id="addCommentForm"'
				);
				echo $form->field($comment, 'content', 'Comment')
					->setHolder('Comment Content')
					->required();
				echo $form->submit('comment', 'class=""');
				$form::end();

				?>
            </div>
            <div>
                <table id="commentsTable">
					<?php
                    /** post comments table content */
					$comments = $comment->findAllBy(['post' => $post->id]);
					foreach ($comments as $com) {
						echo '<tr>';
						$user = $comment->user($com['user']);
						echo '<td>' . $user->name . '</td> ';
						echo '<td>' . $com['content'] . '</td>';
						echo '</tr>';
					}
					?>
                </table>
            </div>
        </div>
        <div class="usersLikes">
            <div class="content">
                <span class="fa fa-close close" onclick="hideLikes()"></span>
                Hello World
            </div>
        </div>
    </div>
</div>