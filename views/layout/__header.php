<?php
use Simfa\Framework\Application;

?>

<style>

	.c-container {
		width: 80%;
		margin: auto;
	}

	.c-header {
		padding: 15px 0;
		background-color: #FFF;
		box-shadow: 0px 18px 30px rgb(0, 0, 0, .1);
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		z-index: 2;
	}

	.c-header .c-header-stl {
		display: flex;
        justify-content: space-between;
        align-items: center;
	}

	.c-header .c-header-stl .c-logo h1 {
		color: #167ede;
	}

	.c-header .c-header-stl .c-nav {
		list-style: none;
	}
	
	.c-header .c-header-stl .c-nav li {
		display: inline-block;
		padding: 10px 20px;
		position: relative;
		font-size: 1.2rem;
		font-weight: 600;
	}

	.c-header .c-header-stl .c-nav li .btn-logout {
		all: unset;
		transition: all .3s linear;
		cursor: pointer;
	}

	.c-header .c-header-stl .c-nav li:hover .btn-logout {
		padding: 5px;
		background-color: #167ede;
		color: #FFF;
		border: 1px solid #FFF;
		border-radius: 5px;
	}

	.c-header .c-header-stl .c-nav li a {
		text-decoration: none;
		color: #5f5f5f;
		text-transform: capitalize;
	}

	.c-header .c-header-stl .c-nav li a::after {
		content: "";
		display: block;
		width: 0%;
		height: 4px;
		background-color: #167ede;
		margin-top: 5px;
	}

	.c-header .c-header-stl .c-nav li a:hover:after {
		width: 100%;
		transition: all .3s linear;
	}

	.c-header .c-header-stl .c-menu {
		display: none;
		width: 50px;
		height: 50px;
		cursor: pointer;
		text-align: center;
		position: relative;
		z-index: 1000;
	}

	.c-header .c-header-stl .c-menu span {
		display: block;
		width: 35px;
		height: 5px;
		background-color: #111 !important;
  		margin: 6px 0;
	}

	@media screen and (max-width: 1000px) {
		.c-nav {
			height: 100vh;
			width: 100%;
			background-color: #167ede;
			position: fixed;
			top: 0;
			left: 0;
			padding: 40px 0;
			text-align: center;
			padding-top: 90px;
			transition: all 1s ease-in-out;
			clip-path: circle(150px at 90% -30%);
			z-index: 999;
		}

		.c-nav li {
			display: block !important;
            margin: 60px 0;
		}

		.c-nav li a {
			color: #FFF !important;
		}

		.c-menu {
			display: block !important;
		}

	}

	.open {
        clip-path: circle(1250px at 90% -30%) !important;
    }



</style>

<header class="c-header">
	<div class="c-container">
		<div class="c-header-stl">
			<div class="c-logo">
				<h1>Camagru</h1>
			</div>
			<div class="c-nav">
				<li><a class="active co" href="<?= Application::path('home.index') ?>"><?= $this->lang('home'); ?></a>
				</li>
				<li><a href="<?= Application::path('contact.us') ?>"><?= $this->lang('contact'); ?></a></li>
				<li><a href="#"><?= $this->lang('about'); ?></a></li>
				<?php if (!Application::isGuest()): ?>
					<li><a href="<?= Application::path('camera.index') ?>"><?= $this->lang('camera'); ?></a></li>
				<?php else: ?>
					<li><a href="#" onclick="loginPopUp('<?=route('camera.index')?>')"><?= $this->lang('camera'); ?></a></li>
				<?php endif ?>

				<?php if (Application::isGuest()): ?>
					<li>
						<a href="<?= Application::path('auth.login') ?>?ref=<?= urlencode(Application::$APP->request->getPath()) ?>"><?= $this->lang('login'); ?></a>
					</li>
					<li><a href="<?= Application::path('auth.signup') ?>"><?= $this->lang('register'); ?></a></li>
				<?php else: ?>
					<li><a href="<?= Application::path('user.profile') ?>"><?= $this->lang('profile') ?></a></li>
					<li class="c-logout">
						<a>
							<form action="<?= Application::path('app.logout') ?>" method="post">
								<?php Application::$APP->session->generateCsrf() ?>
								<input type="hidden" name="__csrf" value="<?= Application::$APP->session->getCsrf() ?>">
								<button class="btn-logout"><?= Application::$APP->user->username; ?></button>
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



<script>

	const menuBtn = document.querySelector(".c-menu");

	const cNav = document.querySelector(".c-nav");

	menuBtn.onclick = () => {
		cNav.classList.toggle("open");
	}

</script>
