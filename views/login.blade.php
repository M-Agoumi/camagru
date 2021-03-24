<h1>Sign In</h1>
<!--<form method="post" class="form-login">
    <div class="form-block">
        <label for="username"><small>Username/Email/Phone:</small></label><br>
        <input type="text" id="username" class="input" placeholder="example@mail.com">
    </div>
    <div class="form-block">
        <label for="password"><small>Password:</small></label><br>
        <input type="password" id="password" class="input" placeholder="*****">
    </div>
    <button type="submit">Sign in</button>
</form>-->
<form method="POST" class="login-form">
    <div class="row">
        <div class="col-25">
            <label for="username">Username\Email</label>
        </div>
        <div class="col-75">
            <input type="text" id="username" name="username" placeholder="Example@email.com">
        </div>
    </div>
    <div class="row">
        <div class="col-25">
            <label for="password">Password</label>
        </div>
        <div class="col-75">
            <input type="password" id="password" name="password" placeholder="Your Password">
        </div>
    </div>
    <div class="row">
        <input type="submit" value="Sing in">
    </div>
</form>