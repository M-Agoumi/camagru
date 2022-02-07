@layout('main')
@section('title'){{ title }}@endsection
@section('content')
	<?php

	/** @var $post Post */

	use Model\Comments;
	use Model\Likes;
	use Model\Post;
	use Simfa\Form\Form;
	use Simfa\Framework\Application;

	?>
	<div class="center">
	    <h1 class="usernameTitle"><?=$post->title?>
	        <sub><small><em><?=$post->updated_at ? '(Edited)' : ''?></em></small></sub></h1>
	    <img src="/uploads/<?=$post->picture?>" alt="<?=$post->comment ?? $post->title?>">
	    <div class="usernameInfo">
	        <p><?=$post->highlightHashtag($post->comment)?></p>
			<?php
			/** get author name */
			$author = $post->author;

			/** get likes */
			$likes = New Likes();

			$likesCount = $likes->getCount(['post' => $post->id, 'status' => 0]);
			if (Application::$APP->user)
				$liked = $likes->getCount(['post' => $post->id, 'user' => Application::$APP->user->getId(), 'status' => 0]);
			else
				$liked = 0;

			//    echo $post->author;
			?>
	        <p>posted <?=humanTiming(strtotime($post->created_at))?> ago by <span
	                    class="authorName"><?=$author->name?></span></p>
	    </div>
	    <div class="filters">
	    <span class="origin">
	        <span>
	            (<span class="likeCount" onclick="showLikes(<?=$post->id?>)"><?=$likesCount?></span>)

	        <span class="wrapper-like">
	            <?php if ($liked): ?>
	            <span onclick="likePost(<?=$post->id?>, this)">liked</span>
	            <?php else: ?>
	                <div class="icon like">
						<div class="tooltip">like</div>
						<span onclick="likePost(<?=$post->id?>, this, 0)"><i class="fa fa-thumbs-up"></i></span>
					</div>
	            <?php endif; ?>
					<div class="icon love">
						<div class="tooltip">love</div>
						<span onclick="likePost(<?=$post->id?>, this, 1)"><i class="fa fa-heart"></i></span>
					</div>
					<div class="icon wow">
						<div class="tooltip">wow</div>
						<span onclick="likePost(<?=$post->id?>, this, 2)"><i class="fas fa-grin-alt"></i></span>
					</div>
					<div class="icon haha">
						<div class="tooltip">haha</div>
						<span onclick="likePost(<?=$post->id?>, this, 3)"><i class="fas fa-grin-squint-tears"></i></span>
					</div>
					<div class="icon sad">
						<div class="tooltip">sad</div>
						<span onclick="likePost(<?=$post->id?>, this, 4)"><i class="fas fa-sad-tear"></i></span>
					</div>
					<div class="icon angry">
						<div class="tooltip">angry</div>
						<span onclick="likePost(<?=$post->id?>, this, 5)"><i class="fa fa-angry"></i></span>
					</div>
	        </span>
	<?php
		        if (Application::$APP->user && Application::$APP->user->id == $post->author->id):
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
		            $form = Form::begin(
			            '/api/post/comment/' . $post->slug, 'POST', '',
			            'onsubmit="return addComment(event, \'' . $post->slug . '\')" id="addCommentForm"'
		            );
		            echo $form->field($comment, 'content', 'Comment')
			            ->setHolder('Comment Content')
			            ->required();
		            echo $form->submit('comment', '');
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
@endsection
