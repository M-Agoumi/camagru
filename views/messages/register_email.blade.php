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
<form method="post" action="/register_step_2">
	<input type="text" name="verification">
	<?php if (!empty($error)): ?>
		<?=$error?> <a href="#">resend code</a>
		<!-- todo call method to resend email verification code -->
	<?php endif; ?>
</form>
<!-- todo handle this page and code next steps -->
