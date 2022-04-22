@layout('main')
@section('title'){{ title }}@endsection
@section('content')
	<?php

	/** @var $post Post */

	use Helper\TimeHelper;
	use Model\Comments;
	use Model\Post;
	use Model\User;
	use Simfa\Form\Form;
	use Simfa\Framework\Application;

	/**
	 * @var TimeHelper $helper
	 * @var User $author
	 * @var int $likesCount
	 * @var int $liked
	 */
	$helper = Application::$APP->helper->getHelper(TimeHelper::class);
	?>

	<div class="center">
	    <h1 class="usernameTitle"><?=$post->title?>
	        <sub><small><em><?=$post->updated_at ? '(Edited)' : ''?></em></small></sub></h1>
	    <img src="/uploads/<?=$post->picture?>" style="max-width: 677px;" alt="<?=$post->comment ?? $post->title?>">
	    <div class="usernameInfo">
	        <div>
		        <span class="authorName">
			        <a href="<?=route('user.show', $author->getUsername())?>">
				        <?=$author->name?>
			        </a>
		        </span>
		        <span class="posting-time">
		            <?=$helper->humanTiming(strtotime($post->created_at))?> ago
		        </span>
	        </div>
		    <div class="post-comment">
			     <?=$post->highlightHashtag($post->comment)?>
		    </div>
	    </div>
	    <div class="filters">
	    <span class="origin">
	        <span>
	            (<span class="likeCount" id="reactsCounter" onclick="showLikes(<?=$post->entityID?>)"><?=$likesCount?></span>)

	        <span class="wrapper-like">
	            <div class="icon like">
					<div class="tooltip">like</div>
					<span<?= $liked == 0 ? ' class="like-active"' : ''?> onclick="likePost(<?=$post->entityID?>, this, 0)"><i class="fas fa-thumbs-up"></i></span>
				</div>
				<div class="icon love">
					<div class="tooltip">love</div>
					<span<?= $liked == 1 ? ' class="love-active"' : ''?> onclick="likePost(<?=$post->entityID?>, this, 1)"><i class="fa fa-heart"></i></span>
				</div>
				<div class="icon wow">
					<div class="tooltip">wow</div>
					<span<?= $liked == 2 ? ' class="wow-active"' : ''?>  onclick="likePost(<?=$post->entityID?>, this, 2)"><i class="fas fa-grin-alt"></i></span>
				</div>
				<div class="icon haha">
					<div class="tooltip">haha</div>
					<span<?= $liked == 3 ? ' class="haha-active"' : ''?>  onclick="likePost(<?=$post->entityID?>, this, 3)"><i class="fas fa-grin-squint-tears"></i></span>
				</div>
				<div class="icon sad">
					<div class="tooltip">sad</div>
					<span<?= $liked == 4 ? ' class="sad-active"' : ''?>  onclick="likePost(<?=$post->entityID?>, this, 4)"><i class="fas fa-sad-tear"></i></span>
				</div>
				<div class="icon angry">
					<div class="tooltip">angry</div>
					<span<?= $liked == 5 ? ' class="angry-active"' : ''?>  onclick="likePost(<?=$post->entityID?>, this, 5)"><i class="fa fa-angry"></i></span>
				</div>
	        </span>
			<?php if (Application::$APP->user && Application::$APP->user->getId() == $post->author->getId()):?>
				<a href="/post/delete/<?=$post->getSlug() . '?' . Application::$APP->session->getToken('post')?>"><span>Delete Post</span></a>		
			<?php endif; ?>
	    </span>
	        <div>
	            <div>
					<?php
		            $comment = New Comments();
		            $form = Form::begin(
			            '/api/post/comment/' . $post->slug, 'POST', '',
			            'onsubmit="return addComment(event, \'' . $post->slug . '\')" id="addCommentForm"',
					'csrf_comment'
		            );
		            echo $form->field($comment, 'content')
			            ->setHolder('Comment Content')->noLabel()->addSubmit('comment')->noAutocomplete();
		            $form::end();

		            ?>
	            </div>
	            <div>
	                <table id="commentsTable">
						<?php
		                /** post comments table content */
		                $comments = $comment->findAllBy(['post' => $post->entityID]);
		                foreach ($comments as $com) {
			                echo '<tr>';
			                $user = $comment->user($com['user']);
			                echo '<td><a href="' . route('user.show',$user->getUsername()) .'">' . $user->name . '</a></td> ';
			                echo '<td>' . $com['content'] . '</td>';
			                echo '</tr>';
		                }
		                ?>
	                </table>
	            </div>
	        </div>
	        <div class="usersLikes" style="display: none">
	            <div class="content">
					<span class="fa fa-close close" onclick="hideLikes()" ></span>
					Hello World
	            </div>
	        </div>
	    </div>
	</div>
@endsection
