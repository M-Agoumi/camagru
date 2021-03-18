<?php
?>
<h1>You almost there just fill those fields and welcome onboard</h1>
<?php
    var_dump($user);
?>
<form method="POST" action="/registration" class="login-form">
    <div class="row">
        <div class="col-25">
            <label for="email">Full Name</label>
        </div>
        <div class="col-75">
            <input type="text" id="email" name="name" placeholder="John Dracula">
        </div>
    </div>
    <div class="row">
        <div class="col-25">
            <label for="username">Username</label>
        </div>
        <div class="col-75">
            <input type="text" id="username" name="username" placeholder="john_dr">
        </div>
    </div>
    <div class="row">
        <div class="col-25">
            <label for="email">Email</label>
        </div>
        <div class="col-75">
            <input type="email" disabled="disabled" id="email" name="email" value="<?=$email?>">
        </div>
    </div>
    <div class="row">
        <div class="col-25">
            <label for="password">Password</label>
        </div>
        <div class="col-75">
            <input type="password" id="password" name="password" placeholder="Your Secure Password">
        </div>
    </div>
    <div class="row">
        <input type="submit" value="Sign up">
    </div>
</form>
