<?php
/** @var $user User */
?>
<h1>Welcome to <?=$user->name?> profile</h1>
<p>Name: <?=$user->name?></p>
<p class="center">already friends? <a href="<?=Application::path('auth.login')?>use core\Application;use models\User;">login</a> to see his complete
    profile</p>
