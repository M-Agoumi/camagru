// get the canvas object, so we can modify it without calling
// getElementById over and over
let canvas = document.getElementById("canvas");
let stream;
let video = document.getElementById("video");

async function getWebCam() {
	document.getElementsByClassName('camera_container')[0].style.display = "block";
	// document.getElementById('filler').style.display = 'block';
    try {
        const videoSrc = await navigator.mediaDevices.getUserMedia({video: true, audio: false});
		stream = videoSrc;
        video.srcObject = videoSrc;
        document.getElementById('picture').style.display = 'none';
        document.getElementById('camera').style.display = 'block';
		document.getElementsByClassName('button-container')[0].style.display = 'none';
    } catch (e) {
        console.log(e);
    }
}

function stopWebCam() {
	stream.getTracks()[0].stop();
	video.pause();
	video.src = "";
}

// DEALING WITH MENU BUTTON
let menuBtn  = document.querySelector(".menu");
let navBar = document.querySelector(".nav .nav-left");


menuBtn.onclick = function(e) {
    navBar.classList.toggle("show");
}

async function capture() {
	const canvas = document.getElementById('canvas');
	let context = canvas.getContext('2d');
	canvas.width = video.videoWidth;
	canvas.height = video.videoHeight;
	context.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
	if (video.naturalWidth >= 1080) {
		canvas.style.width = '1080px';
	} else {
		canvas.style.width = video.naturalWidth;
	}
		// context.drawImage(video, 0, 0, 650, 490);

	document.getElementById('picture').style.display = 'block';
	document.getElementById('camera').style.display = 'none';
	stopWebCam();
}

function save() {
	var canvas = document.getElementById("canvas");
	var img    = canvas.toDataURL("image/jpeg");

	var tmp = document.getElementById('tmp');
	tmp.innerHTML = '<img src="'+img+'" alt="tmp image"/>';
	console.log(img);
	document.getElementById('inputPicture').value = img;

	return true;
}

function getUploadForm()
{
	document.getElementsByClassName('button-container')[0].style.display = 'none';
	document.getElementsByClassName('camera_upload_container')[0].style.display = 'block';
}
