<?php

use core\Application;

?>
<header class="page-header">
	<nav>
		<ul>
            <div>
                <li><a class="active" href="<?=Application::path('home.index')?>"><?= $this->lang('home');?></a></li>
                <li><a href="#"><?= $this->lang('news');?></a></li>
                <li><a href="#"><?= $this->lang('contact us  ');?></a></li>
                <li><a href="#"><?= $this->lang('about');?></a></li>
            </div>
            <!-- todo change this and put it in css file -->
            <div style="float: right">
				<?php if (Application::isGuest()):?>
                <li><a href="<?=Application::path('auth.login')?>"><?= $this->lang('login');?></a></li>
                <li><a href="<?=Application::path('auth.signup')?>"><?= $this->lang('register');?></a></li>
            	<?php else: ?>
				<li>
					<a>
						<form action="<?=Application::path('app.logout')?>" method="post">
							<input type="hidden" name="token" value="123123">
							<button><?= Application::$APP->user->username;?></button>
						</form>
					</a>
				</li>
				<?php endif; ?>
			</div>
		</ul>
	</nav>
</header>