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
            <span>brightness <span onclick="brightnessUp()">(+)</span><span onclick="brightnessDown()">(-)</span></span>
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