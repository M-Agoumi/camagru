<h1>Smile to the camera</h1>

<div class="camera_container">
    <div class="camera" id="camera">
        <video autoplay="autoplay" id="video"></video>
        <div class="capture" onclick="capture()">capture</div>
    </div>
    <div class="picture" id="picture">
        <canvas id="canvas" width="650" height="490"></canvas>
        <div class="capture" onclick="save()">confirm image</div>
        <div class="capture" onclick="getWebCam()">Retake</div>
    </div>
    <div>
        <form method="post">
            <input type="hidden" name='picture' id="inputPicture">
            <input type="submit" value="save">
        </form>
    </div>
</div>

<script>
    async function getWebCam() {
        try {
            const videoSrc = await navigator.mediaDevices.getUserMedia({video: true});
            let video = document.getElementById("video");
            video.srcObject = videoSrc;
            document.getElementById('picture').style.display = 'none';
            document.getElementById('camera').style.display = 'block';
        } catch (e) {
            console.log(e);
        }
    }
    getWebCam();
</script>