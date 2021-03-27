<header class="page-header">
	<nav>
		<ul>
            <div>
                <li><a class="active" href="<?=Application::path('home.index')?>"><?= $this->lang('home');?></a></li>
                <li><a href="#news"><?= $this->lang('news');?></a></li>
                <li><a href="#contact"><?= $this->lang('contact us  ');?></a></li>
                <li><a href="#about"><?= $this->lang('about');?></a></li>
            </div>
            <!-- todo change this and put it in css file -->
            <div style="float: right">
                <li><a href="<?=Application::path('auth.login')?>"><?= $this->lang('login');?></a></li>
                <li><a href="<?=Application::path('auth.singup')?>"><?= $this->lang('register');?></a></li>
            </div>
		</ul>
	</nav>
</header>