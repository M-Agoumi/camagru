<?php
    /** @var $user \models\User */
?>
<h1>Welcome back <?=$user->name?></h1>
<p>Name:  <?=$user->name?></p>
<p>Username: <?=$user->username?></p>
<p>EmailL: <?=$user->email?></p>
<p>Status: <?=$user->status ? 'Activated': 'Not Activated'?></p>
<p>Joined: <?=explode(" ",$user->created_at)[0]?></p>

