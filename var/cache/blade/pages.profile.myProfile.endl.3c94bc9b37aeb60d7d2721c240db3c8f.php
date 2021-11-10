<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,600;0,700;1,400&display=swap" />
	<link rel="stylesheet" href="http://localhost:8000/assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="http://localhost:8000/assets/css/style.css">
	<title><?=htmlspecialchars($title)?></title>
</head>
<body>
	<div class="wrapper">
		<?php include("/home/thatSiin/Desktop/camagru/views/layout/__header.php") ?>
		<main class="page-body">
			<div class="container">
																<?php
	/** @var $user User */
	?>

	<div class="my-profile">
		<div class="profile-cover"></div>
		<div class="profile-logo"></div>
		

		<div class="profile-page">
			<div class="profile-header">
				<div class="profile-header-logo">
					<img src="/assets/icon/profil logo.png" alt="Logo">
				</div>
				<div class="profile-header-cover">
					<i class="fa fa-camera"></i> 
					<label for="files">Update cover picture</label>
					<input type="file" name="file" id="files">
				</div>
			</div>
			<div class="profile-body">
				<div class="profile-body-info">
					<h4><?=$user->name?></h4>
					<span class="pf profile-body-info-username">
						<img src="/assets/icon/Group 4.png" alt="Icon"> <?=$user->username?>
					</span>
					<span class="pf profile-body-info-email">
						<img src="/assets/icon/mail.png" alt="Icon"> <?=$user->email?>
					</span>
					<span class="pf profile-body-info-status">
						<img src="/assets/icon/check.png" alt="Icon"> <?=$user->status ? 'Activated' : 'Not Activated'?>
					</span>
					<span class="pf profile-body-info-date">
						<img src="/assets/icon/calander.png" alt="Icon"> <?=explode(" ", $user->created_at)[0]?>
					</span>
					<div class="profile-body-info-buttons">
						<a href="<?=route('user.edit')?>"><i class="fa fa-pencil"></i> Information</a>
						<a href="<?=route('user.preferences')?>"><i class="fa fa-cog"></i> preferences</a>	
					</div>
				</div>
				<div class="profile-body-posts">
					<div class="profile-posts">
						<div class="profile-post">
							<div class="profile-post-info">
								<div class="profile-post-info-header">
									<div class="profile-post-info-logo">
										<img class="user-pic" src="/assets/icon/profil logo.png" alt="Logo">
										<div class="pf-info">
											<h5>Yassine elidrissi</h5>
											<span><i class="fa fa-clock-o"></i> September 28 2021 - 21:13</span>
										</div>
									</div>
									<span class="pf-menu"><img src="/assets/icon/more-icon.png" alt=""></span>
								</div>
							</div>
							<div class="profile-post-statu">
								<p>how do I look ??</p>
							</div>
							<div class="profile-post-image">
								<img src="/assets/icon/1_post.png" alt="Picture">
							</div>
						</div>
					</div>
				</div>
			</div>
				
				
		</div>
		
	</div>
			</div>
		</main>
	</div>
	<?php include("/home/thatSiin/Desktop/camagru/views/layout/__footer.php") ?>
	<script src="http://localhost:8000/assets/js/script.js"></script>
</body>
</html>
