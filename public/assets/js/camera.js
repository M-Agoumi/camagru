// get the canvas object, so we can modify it without calling
// getElementById over and over
let canvas = document.getElementById("canvas");

async function getWebCam() {
    try {
        const videoSrc = await navigator.mediaDevices.getUserMedia({video: true, audio: false});
        let video = document.getElementById("video");
        video.srcObject = videoSrc;
        document.getElementById('picture').style.display = 'none';
        document.getElementById('camera').style.display = 'block';
    } catch (e) {
        console.log(e);
    }
}

getWebCam();

// DEALING WITH MENU BUTTON
let menuBtn  = document.querySelector(".menu");
let navBar = document.querySelector(".nav .nav-left");


menuBtn.onclick = function(e) {
    navBar.classList.toggle("show");
}

async function capture() {
	const canvas = document.getElementById('canvas');
	let context = canvas.getContext('2d');

	context.drawImage(video, 0, 0, 650, 490);
	document.getElementById('picture').style.display = 'block';
	document.getElementById('camera').style.display = 'none';
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
