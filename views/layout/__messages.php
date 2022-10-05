<?php use Simfa\Framework\Application; ?>
<?php if (Application::$APP->session->getFlash('success')): ?>
	<div class="alert alert-success" id="flash_message">
		<?= Application::$APP->session->getFlash('success') ?>
		<span href="#" onclick="dismissMessage()">x</span>
	</div>
<?php endif; ?>
<?php if (Application::$APP->session->getFlash('error')): ?>
	<div class="alert alert-error" id="flash_message">
		<?= Application::$APP->session->getFlash('error') ?>
		<span href="#" onclick="dismissMessage()">x</span>
	</div>
<?php endif; ?>
<?php if (Application::$APP->session->get('post-tmp-image') && route('camera.save') != Application::$APP->request->getPath()): ?>
<!--	--><?php //Application::$APP->session->unset('post-tmp-image'); ?>
	<div class="alert alert-info" id="flash_message">
		we noticed you have a none published image, <a href="/camera/share">share it</a>
		<span href="#" onclick="dismissMessage(1)">x</span>
	</div>
<?php endif; ?>
