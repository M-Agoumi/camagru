<?php
use core\Application;
/** @var $post \models\Post */
?>
<div class="center">
    <h1><?=$post->title?> <sub><small><em><?=$post->updated_at ? '(Edited)' : ''?></em></small></sub></h1>
    <img src="/uploads/<?=$post->picture?>" alt="<?=$post->comment ?? $post->title?>">
    <p><?=$post->comment?></p>
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

	$likesCount = $likes->getCount(['post' => $post->id]);
	if (Application::$APP->user)
		$liked = $likes->getCount(['post' => $post->id, 'user' => Application::$APP->user->getId()]);
	else
		$liked = 0;

	//    echo $post->author;
	?>
    <p>posted <?=humanTiming(strtotime($post->created_at))?> ago by <?=$author?></p>
    <div class="filters">
    <span class="origin">
        <span>
            (<span><?=$likesCount?></span>)
            <?php if ($liked): ?>
            <span onclick="likePost(<?=$post->id?>, this)">liked</span>
            <?php else: ?>
                <span onclick="likePost(<?=$post->id?>, this)">like</span>
            <?php endif; ?>
        </span>
<?php
	    if (Application::$APP->user && Application::$APP->user->id == $post->author):
	    ?>
        <span>edit post</span>
<?php endif; ?>
    </span>
    </div>
</div>