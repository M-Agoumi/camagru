<h1>Smile to the camera</h1>

<div class="camera_container">
    <div class="camera" id="camera">
        <video autoplay="autoplay" id="video" width="650" height="490"></video>
        <div class="capture" onclick="capture()">capture</div>
    </div>
    <div class="picture" id="picture">
        <canvas id="canvas" width="650" height="460"></canvas>
        <div class="capture" onclick="save()">confirm image</div>
        <div class="capture" onclick="getWebCam()">Retake</div>
        <div class="filters">
			<span onclick="clearFilters()">Original</span>
            <span>
				brightness:
				<span onclick="brightnessToggle()">bright</span>
				<span onclick="brightnessUp()">(+)</span>
				<span onclick="brightnessDown()">(-)</span>
			</span>
			<span>
				grayscale:
				<span onclick="grayscaleToggle()">gray</span>
				<span onclick="grayscaleUp()">(+)</span>
				<span onclick="grayscaleDown()">(-)</span>
			</span>
			<span>
				blur:
				<span onclick="blurToggle()">blur</span>
				<span onclick="blurUp()">(+)</span>
				<span onclick="blurDown()">(-)</span>
			</span>
			<span>
				contrast:
				<span onclick="contrastToggle()">contrast</span>
				<span onclick="contrastUp()">(+)</span>
				<span onclick="contrastDown()">(-)</span>
			</span>
			<span>
				hue:
				<span onclick="hueToggle()">hue</span>
				<span onclick="hueUp()">(+)</span>
				<span onclick="hueDown()">(-)</span>
			</span>
			<span>
				invert:
				<span onclick="invertToggle()">invert</span>
				<span onclick="invertUp()">(+)</span>
				<span onclick="invertDown()">(-)</span>
			</span>
		</div>
    </div>
    <div>
        <form method="post">
            <input type="hidden" name='picture' id="inputPicture">
            <input type="submit" value="save">
        </form>
    </div>
</div>

<script src="/assets/js/camera.js"></script>