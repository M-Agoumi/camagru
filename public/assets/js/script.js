function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function dismissMessage() {
    let source = document.getElementById('flash_message');
    source.classList.toggle('fade');
    await sleep(1000);
    source.style.display = "none";
}

async function getWebCam() {
    try {
        const videoSrc = await navigator.mediaDevices.getUserMedia({video: true});
        let video = document.getElementById("video");
        video.srcObject = videoSrc;
    } catch (e) {
        console.log(e);
    }
}

const capture = document.getElementById('capture');
const canvas = document.getElementById('canvas');
let context = canvas.getContext('2d');

capture.addEventListener("click", function (){
    context.drawImage(video, 0,0,650, 490);
});

getWebCam();