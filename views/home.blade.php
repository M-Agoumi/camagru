<?php
use core\Application;
/**
 * @var $title
 * @var $test
 */
?>
<title><?=$title?></title>
<h1><?=Application::$APP->lang('home')?> <?= $test ?></h1>
<?php
/** @var $postModule \models\Post */
$posts = $postModule->findAll();
//var_dump($posts);
?>
<div class="masonry-container">
    <!-- =============================================== -->
    <div class="gal-one">
        <?php foreach ($posts as $post): ?>
        <a href="/post/<?=$post['slug']?>">
            <div class="panel">
                <div class="panel-wrapper">
                    <div class="panel-overlay">
                        <div class="panel-text">
                            <div class="panel-title"><?=$post['title']?></div>
                            <div class="panel-tags">
                                <?php
                                $tags = $postModule->hashtag($post['comment']);
                                if ($tags):
                                ?>
                                <span class="tag-icon"><img class="tag-icon-img"
                                                            src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/tag-icon.svg"
                                                            alt=""/></span>
                                <span class="tags-list"><?=$postModule->hashtag($post['comment']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <img class="panel-gradient"
                            src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/base-gradient.png" alt=""/>
                        <img class="panel-vingette"
                            src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/darken-gradient.png" alt=""/>
                    </div>
                    <img class="panel-img" src="<?= '/uploads/' . $post['picture']?>" alt=""/>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
        <div class="panel panel-one">
            <div class="panel-wrapper">
                <div class="panel-overlay">
                    <div class="panel-text">
                        <div class="panel-title">Crashing Waves</div>
                        <div class="panel-tags">
                            <span class="tag-icon"><img class="tag-icon-img"
                                                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/tag-icon.svg"
                                                        alt=""/></span>
                            <span class="tags-list">Landscapes, Waves, Beach</span>
                        </div>
                    </div>
                    <img class="panel-gradient" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/base-gradient.png"
                        alt=""/>
                    <img class="panel-vingette"
                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/darken-gradient.png" alt=""/>
                </div>
                <img class="panel-img" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/waves.jpg" alt=""/>
            </div>
        </div>

        <div class="panel panel-two">
            <div class="panel-wrapper">
                <div class="panel-overlay">
                    <div class="panel-text">
                        <div class="panel-title">Blue Docks</div>
                        <div class="panel-tags">
                            <span class="tag-icon"><img class="tag-icon-img"
                                                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/tag-icon.svg"
                                                        alt=""/></span>
                            <span class="tags-list">Docks, Sunset, Horizon</span>
                        </div>
                    </div>
                    <img class="panel-gradient" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/base-gradient.png"
                        alt=""/>
                    <img class="panel-vingette"
                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/darken-gradient.png" alt=""/>
                </div>
                <img class="panel-img" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/docks.jpg" alt=""/>
            </div>
        </div>

        <div class="panel panel-three">
            <div class="panel-wrapper">
                <div class="panel-overlay">
                    <div class="panel-text">
                        <div class="panel-title">Pastel Canyons</div>
                        <div class="panel-tags">
                            <span class="tag-icon"><img class="tag-icon-img"
                                                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/tag-icon.svg"
                                                        alt=""/></span>
                            <span class="tags-list">Canyon, Rock formations</span>
                        </div>
                    </div>
                    <img class="panel-gradient" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/base-gradient.png"
                        alt=""/>
                    <img class="panel-vingette"
                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/darken-gradient.png" alt=""/>
                </div>
                <img class="panel-img" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/canyon.jpg" alt=""/>
            </div>
        </div>
    </div>
    <!-- =============================================== -->

    <!-- =============================================== -->
    <div class="gal-two">
        <div class="panel panel-four">
            <div class="panel-wrapper">
                <div class="panel-overlay">
                    <div class="panel-text">
                        <div class="panel-title">Pink Mountain Sunset</div>
                        <div class="panel-tags">
                            <span class="tag-icon"><img class="tag-icon-img"
                                                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/tag-icon.svg"
                                                        alt=""/></span>
                            <span class="tags-list">Landscapes, Sunset, Mountains</span>
                        </div>
                    </div>
                    <img class="panel-gradient" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/base-gradient.png"
                        alt=""/>
                    <img class="panel-vingette"
                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/darken-gradient.png" alt=""/>
                </div>
                <img class="panel-img" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/dawn-mountains.jpg" alt=""/>
            </div>
        </div>

        <div class="panel panel-five">
            <div class="panel-wrapper">
                <div class="panel-overlay">
                    <div class="panel-text">
                        <div class="panel-title">Autumn Pine</div>
                        <div class="panel-tags">
                            <span class="tag-icon"><img class="tag-icon-img"
                                                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/tag-icon.svg"
                                                        alt=""/></span>
                            <span class="tags-list">Forest, Pines, Mountains</span>
                        </div>
                    </div>
                    <img class="panel-gradient" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/base-gradient.png"
                        alt=""/>
                    <img class="panel-vingette"
                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/darken-gradient.png" alt=""/>
                </div>
                <img class="panel-img" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/forest.jpg" alt=""/>
            </div>
        </div>

        <div class="panel panel-six">
            <div class="panel-wrapper">
                <div class="panel-overlay">
                    <div class="panel-text">
                        <div class="panel-title">Purple Power Poles</div>
                        <div class="panel-tags">
                            <span class="tag-icon"><img class="tag-icon-img"
                                                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/tag-icon.svg"
                                                        alt=""/></span>
                            <span class="tags-list">Urban, landscape, manmade</span>
                        </div>
                    </div>
                    <img class="panel-gradient" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/base-gradient.png"
                        alt=""/>
                    <img class="panel-vingette"
                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/darken-gradient.png" alt=""/>
                </div>
                <img class="panel-img" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/power-poles.jpg" alt=""/>
            </div>
        </div>
    </div>
    <!-- =============================================== -->

    <!-- =============================================== -->
    <div class="gal-three">
        <div class="panel panel-seven">
            <div class="panel-wrapper">
                <div class="panel-overlay">
                    <div class="panel-text">
                        <div class="panel-title">Purple Ridges</div>
                        <div class="panel-tags">
                            <span class="tag-icon"><img class="tag-icon-img"
                                                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/tag-icon.svg"
                                                        alt=""/></span>
                            <span class="tags-list">Mountains, Sunrise, Landscapes</span>
                        </div>
                    </div>
                    <img class="panel-gradient" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/base-gradient.png"
                        alt=""/>
                    <img class="panel-vingette"
                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/darken-gradient.png" alt=""/>
                </div>
                <img class="panel-img" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/purple-mtn.jpg" alt=""/>
            </div>
        </div>

        <div class="panel panel-eight">
            <div class="panel-wrapper">
                <div class="panel-overlay">
                    <div class="panel-text">
                        <div class="panel-title">Spinning Lights</div>
                        <div class="panel-tags">
                            <span class="tag-icon"><img class="tag-icon-img"
                                                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/tag-icon.svg"
                                                        alt=""/></span>
                            <span class="tags-list">Carnival, Lights, Nightlife</span>
                        </div>
                    </div>
                    <img class="panel-gradient" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/base-gradient.png"
                        alt=""/>
                    <img class="panel-vingette"
                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/darken-gradient.png" alt=""/>
                </div>
                <img class="panel-img" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/rides.jpg" alt=""/>
            </div>
        </div>

        <div class="panel panel-nine">
            <div class="panel-wrapper">
                <div class="panel-overlay">
                    <div class="panel-text">
                        <div class="panel-title">Feather Bokeh</div>
                        <div class="panel-tags">
                            <span class="tag-icon"><img class="tag-icon-img"
                                                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/tag-icon.svg"
                                                        alt=""/></span>
                            <span class="tags-list">Close-ups, Feather, Bokeh</span>
                        </div>
                    </div>
                    <img class="panel-gradient" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/base-gradient.png"
                        alt=""/>
                    <img class="panel-vingette"
                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/darken-gradient.png" alt=""/>
                </div>
                <img class="panel-img" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/feather.jpg" alt=""/>
            </div>
        </div>

        <div class="panel panel-ten">
            <div class="panel-wrapper">
                <div class="panel-overlay">
                    <div class="panel-text">
                        <div class="panel-title">Irridescent Bench</div>
                        <div class="panel-tags">
                            <span class="tag-icon"><img class="tag-icon-img"
                                                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/tag-icon.svg"
                                                        alt=""/></span>
                            <span class="tags-list">Landscapes, Still-Life, Countryside</span>
                        </div>
                    </div>
                    <img class="panel-gradient" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/base-gradient.png"
                        alt=""/>
                    <img class="panel-vingette"
                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/darken-gradient.png" alt=""/>
                </div>
                <img class="panel-img" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/bench-alt.jpg" alt=""/>
            </div>
        </div>

        <div class="panel panel-eleven">
            <div class="panel-wrapper">
                <div class="panel-overlay">
                    <div class="panel-text">
                        <div class="panel-title">Blazing Dandelions</div>
                        <div class="panel-tags">
                            <span class="tag-icon"><img class="tag-icon-img"
                                                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/tag-icon.svg"
                                                        alt=""/></span>
                            <span class="tags-list">Dandelion, Bokeh, Flora</span>
                        </div>
                    </div>
                    <img class="panel-gradient" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/base-gradient.png"
                        alt=""/>
                    <img class="panel-vingette"
                        src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/darken-gradient.png" alt=""/>
                </div>
                <img class="panel-img" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/375042/bokeh-dandelion.jpg"
                    alt=""/>
            </div>
        </div>
    </div>
    <!-- =============================================== -->
</div>