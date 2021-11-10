<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="http://localhost:8000/assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="http://localhost:8000/assets/css/style.css">
	<title><?=htmlspecialchars($title)?></title>
</head>
<body>
	<div class="wrapper">
		<?php include("/home/thatSiin/Desktop/camagru/views/layout/__header.php") ?>
		<main class="page-body">
			<div class="container">
																<h1>Smile to the camera</h1>

	<div class="camera_container">
	    <div class="camera" id="camera">
	        <video autoplay="autoplay" id="video" width="650" height="490"></video>
	        <div class="capture" onclick="capture()">capture</div>
	    </div>
	    <div id="tmp">

	    </div>
	    <div class="picture" id="picture">
	        <canvas id="canvas" width="650" height="460"></canvas>
	        <div class="pic-btn">
	            <div class="capture confirm" onclick="save()">confirm image</div>
	            <div class="capture retake" onclick="getWebCam()">Retake</div>
	        </div>
	        <div class="filters">
	            <span class="origin" onclick="clearFilters()">Original</span>
	            <span class="bright">
					<!-- brightness: -->
					<span onclick="brightnessToggle()">bright</span>
					<span onclick="brightnessUp()">(+)</span>
					<span onclick="brightnessDown()">(-)</span>
				</span>
	            <span class="gray">
					<!-- grayscale: -->
					<span onclick="grayscaleToggle()">gray</span>
					<span onclick="grayscaleUp()">(+)</span>
					<span onclick="grayscaleDown()">(-)</span>
				</span>
	            <span class="blur">
					<!-- blur: -->
					<span onclick="blurToggle()">blur</span>
					<span onclick="blurUp()">(+)</span>
					<span onclick="blurDown()">(-)</span>
				</span>
	            <span class="contrast">
					<!-- contrast: -->
					<span onclick="contrastToggle()">contrast</span>
					<span onclick="contrastUp()">(+)</span>
					<span onclick="contrastDown()">(-)</span>
				</span>
	            <span class="hue">
					<!-- hue: -->
					<span onclick="hueToggle()">hue</span>
					<span onclick="hueUp()">(+)</span>
					<span onclick="hueDown()">(-)</span>
				</span>
	            <span class="invert">
					<!-- invert: -->
					<span onclick="invertToggle()">invert</span>
					<span onclick="invertUp()">(+)</span>
					<span onclick="invertDown()">(-)</span>
				</span>
	        </div>
	    </div>
	    <div class="btn-save">
	        <form method="post">
				<?php use core\Application;Application::$APP->session->generateCsrf();?>
	            <input type="hidden" name="__csrf" value="<?=Application::$APP->session->getCsrf()?>">
	            <input type="hidden" name='picture' id="inputPicture">
	            <input type="submit" value="save">
	        </form>
	    </div>
	</div>

	<script src="/assets/js/camera.js"></script>
			</div>
		</main>
	</div>
	<?php include("/home/thatSiin/Desktop/camagru/views/layout/__footer.php") ?>
	<script src="http://localhost:8000/assets/js/script.js"></script>
</body>
</html>
