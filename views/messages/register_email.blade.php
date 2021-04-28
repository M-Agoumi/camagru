<?php
/**
 * check if we came from our AuthController otherwise
 * show 403 forbidden or just redirect home
 */
$email = $_SESSION['user_email'] ?? '';
if (!$email)
	header('location: /');
?>
<h1>One More Step Before Joining Us</h1>
<p>We have sent a code to <?=$email?> enter it to continue your sign up</p>
<form method="post" action="/register_step_2">
    <?php \core\Application::$APP->session->generateCsrf();?>
    <input type="hidden" name="__csrf" value="<?=\core\Application::$APP->session->getCsrf()?>">
	<input type="text" name="verification">
	<?php if (!empty($error)): ?>
		<?=$error?> <a href="#">resend code</a>
		<!-- todo call method to resend email verification code -->
	<?php endif; ?>
</form>
<!-- todo handle this page and code next steps -->
