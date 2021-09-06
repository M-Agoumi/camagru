@layout('main')
@section('title'){{ title }}@endsection
@section('content')
    <?php
    use core\Application;use models\Post;
    /**
     * @var $title
     * @var $test
     */
    ?>
    {{--test--}}
    <h1><?=Application::$APP->lang('home')?> {{ test }}</h1>
    <?php
    /** @var $postModule Post */
    $posts = $postModule->findAll();
    ?>
    <div class="masonry-container">
        <!-- =============================================== -->
        <div class="gal-one">
            <?php foreach ($posts as $post): ?>
                <div class="panel">
                    <a href="/post/<?=$post['slug']?>">
                        <div class="panel-wrapper">
                            <div class="panel-overlay">
                                <div class="panel-text">
                                    <div class="panel-title"><?=$post['title']?></div>
                                    <div class="panel-tags">
                                        <?php
                                        $tags = $postModule->hashtag($post['comment']);
                                        if ($tags):
                                        ?>
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
                            <img class="panel-img" src="<?= '/uploads/' . $post['picture']?>" alt=""/>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- =============================================== -->
    </div>
@endsection
