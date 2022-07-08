@layout('main')
@section('title'){{ title }} @endsection
@section('head')
<style>
	.multiChoiceSelectButton {
		all: unset;
		background: var(--mainColor);
		border: 1px solid darkblue;
		color: #FFF;
		padding: 10px;
		font-size: 1.2em;
		font-weight: bold;
		border-radius: 5px;
		cursor: pointer;
		margin-top: 10px;
	}

	.profile-form {
		display: none;
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		background: #fcf8e3;
		width: 80vw;
		height: 60vh;
		border: 2px solid #000000;
		border-radius: 5px;
		padding: 10px;
		z-index: 2;
	}

	.profile-form .action {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
	}

	.profile-form .container-relative {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		background: transparent;
		height: 100%;
		width: 100%;
		overflow: auto;
		display: none;
		padding-top: 100px;
	}

	.profile-form .container-relative::-webkit-scrollbar {
		display: none;
	}
	.profile-form .images-container {
		position: relative;
		padding: 10px;
		text-align: center;
	}

	.profile-form .custom_images {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
	}

	.profile-form .profile_images {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
	}

	.profile-form #message {
		font-size: 1.2em;
		font-weight: bold;
		text-align: center;
		margin-top: 25px;
		z-index: 5;
	}
	
	.profile-form .images-collection {
		position: static;
		width: 100%;
		height: 100%;
		padding: 10px;
	}

	.black-screen {
		background: rgba(0,0,0,0.6);
		width: 100%;
		height: 100%;
		position: absolute;
		top: 0;
		left: 0;
		z-index: 1;
		display: none;
	}

	.profile-page .profile-header{
		position: relative;
	}

	@media only screen and (max-width: 900px) {
		.profile-page .profile-header{
			margin-bottom: 75px;
		}

		.profile-page .profile-body-info {
			width: 100%;
			max-width: 550px;
			margin: auto;
		}

		.profile-page .profile-header .profile-header-logo {
			position: absolute;
			top: 100%;
			left: 50%;
			transform: translate(-50%, -50%);
			width: auto;
			height: auto;
		}

		.profile-page .profile-header .profile-header-logo > img {
			position: initial;
			width: 250px;
			height: 250px;
			transform: none;
		}

		.profile-body-info .profile-body-info-buttons {
			flex-wrap: wrap;
		}

		.profile-body-info .profile-body-info-buttons a {
			flex: 0 0 100%;
			margin-bottom: 5px;
		}

		.profile-page .profile-body-info h4 {
			text-align: center;
			text-transform: capitalize;
		}

		.profile-page .profile-body-posts {
			width: 100%;
		}
	}
</style>
@endsection
@section('content')
<?php
/**
 * @var $user User
 * @var $helper TimeHelper
 */

use Helper\TimeHelper;
use Model\User;
use Simfa\Framework\Application;

$helper = Application::$APP->helper->getHelper(TimeHelper::class);
?>
	<div class="my-profile">
		<div class="profile-page">
			<div class="profile-header" style="background-image: url('/uploads/cover/{{cover}}'); background-repeat: no-repeat; background-attachment: fixed;background-position: center;">
				<div class="profile-header-logo">
					<img src="/uploads/dps/<?=$user->getPicture() ?? 'default.jpg'?>" alt="Logo">
				</div>
				<div class="profile-header-cover"">
				</div>
				<span onclick="showControl()">
					<i class="fas fa-ellipsis-v"></i>
				</span>
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
						<img src="/assets/icon/check.png" alt="Icon"> <?=!$user->status ? 'Activated' : 'Not Activated'?>
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
					<?php foreach ($posts as $post) :?>
					<div class="profile-posts">
						<div class="profile-post">
							<div class="profile-post-info">
								<div class="profile-post-info-header">
									<div class="profile-post-info-logo">
										<img class="user-pic" src="/uploads/dps/<?=$user->getPicture()??'default.jpg'?>" alt="Logo">
										<div class="pf-info">
											<h5>{{ user->getName() }}</h5>
											<span><i class="fa fa-clock-o"></i> <?=$helper->humanTiming(strtotime($post['created_at']))?> ago</span>
										</div>
									</div>
									<span class="pf-menu"><img src="/assets/icon/more-icon.png" alt=""></span>
								</div>
							</div>
							<div class="profile-post-statu">
								<p>{{ post['title'] }}</p>
							</div>
							<div class="profile-post-image">
								<a href="/post/{{ post['slug'] }}">
									<img src="/uploads/post/{{ post['picture'] }}" alt="Picture">
								</a>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="black-screen" onclick="hideControl()"></div>
	<div class="profile-form">
		<div class="action">
			<div id="first-action">
				<button class="multiChoiceSelectButton" onclick="selectDp()">update profile picture</button>
				<button class="multiChoiceSelectButton" onclick="selectCover()">update cover picture</button>
			</div>
			<div id="second-action" style="display: none">
				<button class="multiChoiceSelectButton" onclick="selectDefinedImages()">From images</button>
				<button class="multiChoiceSelectButton" onclick="selectCustomImage()">Custom image</button>
				<button class="multiChoiceSelectButton btn-danger" onclick="deleteCover()">Delete cover</button>
				<button class="multiChoiceSelectButton" onclick="backHome()">back</button>
			</div>
		</div>
		<div class="container-relative">
			<div class="images-container">
				<button class="multiChoiceSelectButton" onclick="submitCover()">validate</button>
				<button class="multiChoiceSelectButton" onclick="backToSecondAction()">back</button>
				<div class="images-collection"></div>
				<div style="clear: left"></div>
			</div>
		</div>
		<div class="custom_images">
			<label for="files">Update cover picture</label>
			<input type="file" class="multiChoiceSelectButton" name="file" id="file_id">
			<input type="button" id="btn_uploadfile"
			       value="Upload"
			       class="multiChoiceSelectButton"
			       onclick="uploadFile(1);" >
			<button class="multiChoiceSelectButton" onclick="backToSecondAction()">back</button>
		</div>
		<div class="profile_images">
			<label for="files">Update profile picture</label>
			<input type="file" class="multiChoiceSelectButton" name="file" id="dp-file">
			<input type="button" id="btn_uploadfile"
			       value="Upload"
			       class="multiChoiceSelectButton"
			       onclick="uploadProfilePic();" >
			<button class="multiChoiceSelectButton btn-danger" onclick="deleteDp()">delete profile picture</button>
			<button class="multiChoiceSelectButton" onclick="backHome()">back</button>
		</div>
		<div id="message">

		</div>
	</div>
	<input type="hidden" value="@csrf" id="csrf_profile"/>
	<script>
		var file_max_size = <?= Application::getAppConfig('post', 'max_file_size') ?>;
	</script>
	<script src="<?= asset("assets/js/profile.js") ?>"
@endsection


