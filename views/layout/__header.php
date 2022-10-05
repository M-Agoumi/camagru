<?php
use Simfa\Framework\Application;

?>
<header class="c-header">
	<div class="c-container">
		<div class="c-header-stl">
			<div class="c-logo">
				<a href="<?= route('home.index') ?>"><h1>Camagru</h1></a>
			</div>
			<div class="c-nav">
				<li><a class="active co" href="<?= Application::path('home.index') ?>"><?= $this->lang('home'); ?></a>
				</li>
				<li><a href="<?= Application::path('contact.us') ?>"><?= $this->lang('contact'); ?></a></li>
				<?php if (!Application::isGuest()): ?>
					<li><a href="<?= Application::path('camera.index') ?>"><?= $this->lang('camera'); ?></a></li>
				<?php else: ?>
					<li><a href="#" onclick="loginPopUp('<?=route('camera.index')?>')"><?= $this->lang('camera'); ?></a></li>
				<?php endif ?>

				<?php if (Application::isGuest()): ?>
					<li>
						<a href="<?= Application::path('auth.login') ?><?=Application::$APP->request->getPath() ? '?ref=' . urlencode(Application::$APP->request->getPath(true)) : '' ?>"><?= $this->lang('login'); ?></a>
					</li>
					<li><a href="<?= Application::path('auth.signup') ?>"><?= $this->lang('register'); ?></a></li>
				<?php else: ?>
					<li><a href="<?= Application::path('user.profile') ?>"><?= $this->lang('profile') ?></a></li>
					<li class="c-logout">
						<a>
							<form action="<?= Application::path('app.logout') ?>" method="post">
								<?php Application::$APP->session->generateCsrf() ?>
								<input type="hidden" name="__csrf" value="<?= Application::$APP->session->getCsrf() ?>">
								<button class="btn-logout"><?= Application::$APP->user->getUsername(); ?>(<?=lang('logout')?>)</button>
							</form>
						</a>
					</li>
				<?php endif; ?>
			</div>
			<div class="c-menu">
				<span></span>
				<span></span>
				<span></span>
				<!-- <i class="fas fa-bars"></i> -->
			</div>
		</div>
	</div>
</header>
