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

/** image filters functions */

// increase the brightness of the image
var brightness = 1.0;

function brightnessUp()
{
    var canvas = document.getElementById("canvas");
    if (brightness < 1.6)
        brightness += 0.1;
    canvas.style.filter = "brightness(" + brightness + ")";
}

// decrease the brightness of the image
function brightnessDown()
{
    if (brightness > 0.5)
        brightness -= 0.1;
    console.log(brightness);
}