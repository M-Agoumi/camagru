<?php use Simfa\Framework\Application; ?>
<?php if (Application::$APP->session->getFlash('success')): ?>
	<div class="alert alert-success" id="flash_message">
		<?= Application::$APP->session->getFlash('success') ?>
		<span href="#" onclick="dismissMessage()">x</span>
	</div>
<?php endif; ?>
<?php //todo optimize this ?>
<?php if (Application::$APP->session->getFlash('error')): ?>
	<div class="alert alert-error" id="flash_message">
		<?= Application::$APP->session->getFlash('error') ?>
		<span href="#" onclick="dismissMessage()">x</span>
	</div>
<?php endif; ?>
