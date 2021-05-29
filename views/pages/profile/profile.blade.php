<?php
/** @var $user \models\User */
?>
<h1>Welcome to <?=$user->name?> profile</h1>
<p>Name: <?=$user->name?></p>
<p class="center">already friends? <a href="<?=\core\Application::path('auth.login')?>">login</a> to see his complete
    profile</p>