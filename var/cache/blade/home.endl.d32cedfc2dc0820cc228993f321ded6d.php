<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="http://localhost:8000/assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="http://localhost:8000/assets/css/style.css">
	<title><?=htmlspecialchars($title)?></title>
</head>
<body>
	<div class="wrapper">
		<?php include("/home/thatSiin/Desktop/camagru/views/layout/__header.php") ?>
		<main class="page-body">
			<div class="container">
																<h1><?=lang('home')?></h1>
    <div class="masonry-container">
        <!-- =============================================== -->
        <div class="gal-one">
            <?php foreach ($posts as $post): ?>
                <div class="panel">
                    <a href="/post/<?=htmlspecialchars($post['slug'])?>">
                        <div class="panel-wrapper">
                            <div class="panel-overlay">
                                <div class="panel-text">
                                    <div class="panel-title"><?=htmlspecialchars($post['title'])?></div>
                                    <div class="panel-tags">
                                        <?php
                                            $tags = $postModule->hashtag($post['comment']);
                                        ?>
                                        <?php if($tags):?>:
	                                        <span class="tag-icon">
	                                            <img class="tag-icon-img" src="/uploads/tag-icon.svg" alt=""/>
	                                        </span>
	                                        <span class="tags-list"><?=$postModule->hashtag($post['comment']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <img class="panel-gradient" src="/uploads/base-gradient.png" alt=""/>
                                <img class="panel-vingette" src="/uploads/darken-gradient.png" alt=""/>
                            </div>
                            <img class="panel-img" src="/uploads/<?=htmlspecialchars($post['picture'])?>" alt=""/>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- =============================================== -->
	    <div style="color: #000000; text-align: center">
		    pages:
		    <?php foreach($postModule->pages() as $page): ?>
				<?php if (is_array($page)): ?>
					<a href="?page=<?=htmlspecialchars($page['active'])?>" class="active" style="color: #FF0000;text-decoration: underline"><?=htmlspecialchars($page['active'])?></a>
				<?php else: ?>
		            <a href="?page=<?=htmlspecialchars($page)?>" style="color: #ff4e00"><?=htmlspecialchars($page)?></a>
	            <?php endif;?>
		    <?php endforeach; ?>
	    </div>
    </div>
			</div>
		</main>
	</div>
	<?php include("/home/thatSiin/Desktop/camagru/views/layout/__footer.php") ?>
	<script src="http://localhost:8000/assets/js/script.js"></script>
</body>
</html>
