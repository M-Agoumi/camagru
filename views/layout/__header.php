<?php

use core\Application;

?>
<header class="page-header">
	<nav>
		<ul class="nav">
            <div class="nav-left">
                <li><a class="active co" href="<?=Application::path('home.index')?>"><?= $this->lang('home');?></a></li>
                <li><a href="#"><?= $this->lang('contact us');?></a></li>
                <li><a href="#"><?= $this->lang('about');?></a></li>
                <li><a href="<?=Application::path('camera.index')?>"><?= $this->lang('Camera');?></a></li>
            </div>
            <!-- todo change this and put it in css file -->
            <div class="nav-right">
				<?php if (Application::isGuest()):?>
                <li><a href="<?=Application::path('auth.login')?>?ref=<?=urlencode(Application::$APP->request->getPath())?>"><?= $this->lang('login');?></a></li>
                <li><a href="<?=Application::path('auth.signup')?>"><?= $this->lang('register');?></a></li>
            	<?php else: ?>
                <li><a href="<?=Application::path('user.profile')?>"><?= $this->lang('profile')?></a></li>
				<li>
					<a>
						<form action="<?=Application::path('app.logout')?>" method="post">
							<?php Application::$APP->session->generateCsrf() ?>
							<input type="hidden" name="__csrf" value="<?=Application::$APP->session->getCsrf()?>">
							<button class="btn-logout"><?= Application::$APP->user->username;?></button>
						</form>
					</a>
				</li>
				<?php endif; ?>
			</div>
			<div class="menu">
				<i class="fa fa-bars"></i>
			</div>
		</ul>
	</nav>
</header>