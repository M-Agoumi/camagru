<?php
/**
 * check if we came from our AuthController otherwise
 * show 403 forbidden or just redirect home
 */

if (!isset($email))
	header('location: /');
?>
<h1>One More Step Before Joining Us</h1>
<p>We have sent a code to <?=$email?> enter it to continue your sign up</p>
<input type="text">
<!-- todo handle this page and code next steps -->
