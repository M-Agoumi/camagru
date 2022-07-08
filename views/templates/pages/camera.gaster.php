@layout('main')
@section('title'){{ title }}@endsection
@section('head')
<style>
	#emote-control-container{
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		z-index: 15;
		position: relative
	}
	#filler {
		background: rgba(0,0,0,0);
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		display: none;
		z-index: 10;
	}

	.camera_container {
		display: none;
	}

	.camera_upload_container {
		display: none;
	}
</style>
@endsection
@section('content')
	<h1>Smile to the camera</h1>
	<div class="button-container">
		<button onclick="getWebCam()">camera</button>
		<button onclick="getUploadForm()">upload</button>
	</div>
	<div class="camera_upload_container">
		<label for="files">chose a file</label>
		<input type="file" class="multiChoiceSelectButton" name="file" id="file">
		<input type="hidden" value="@csrf" />
		<input type="button" id="btn_uploadfile"
			   value="Upload"
			   class="multiChoiceSelectButton"
			   onclick="drawImage();" >
		<img id="tmp-upload" style="display: none" />
	</div>
	<div class="camera_container">
		<div id="filler" onclick="hideFiller()" style="display: none">

		</div>
	    <div class="camera" id="camera">
	        <video autoplay="autoplay" id="video" width="650" height="490" poster="/assets/img/allow-access.gif">
	        </video>
	        <div class="capture" onclick="capture()">capture</div>
	    </div>

	    <div id="tmp">

	    </div>
	    <div class="picture" id="picture">
		    <div id="image" style="position:relative;">
	            <canvas id="canvas"></canvas>
		    </div>
		    <div id="emote-control-container" style="">

		    </div>
		    <div class="camera-emotes">
			    <?php foreach ($emotes as $emote):?>
			    <img src="/assets/img/<?=$emote['file']?>" width="100" onclick="addImage(this.src, '<?=$emote['name']?>')" alt="<?=$emote['name']?>"/>
			    <?php endforeach;?>
		    </div>
	    <div class="btn-save">
	        <form method="post" id="picture-form" onsubmit="save()">
				<?php use Simfa\Framework\Application;Application::$APP->session->generateCsrf();?>
	            <input type="hidden" name="__csrf" value="<?=Application::$APP->session->getCsrf()?>">
	            <input type="hidden" name='picture' id="inputPicture">
	            <div class="camera-btns">
					<div class="submit-btn">
						<input type="submit" value="save">
					</div>
					<div class="capture retake" onclick="getWebCam()">Retake</div>
				</div>
	        </form>
	    </div>
	</div>

	<script src="/assets/js/camera.js"></script>
	<script>

		function hideFiller()
		{
			const filler    = document.getElementById("filler");
			const emote     = document.getElementById('emote_' + filler.dataset.id);
			if (filler.dataset.id) {
				emote.style.border = "none";
				filler.style.display = 'none';
				document.getElementById('emote-control-container').innerText = ''
			}
		}
        /** add pictures to the canvas **/
		let emoteNumber = 1;

        function addImage(imgLink,name) {
            let img = document.createElement("img");
            img.className  = "emotes";
			img.id = 'emote_' + emoteNumber;
            img.src = imgLink;
			img.onclick = sayHi;
			img.style.zIndex = emoteNumber * 5;
			img.dataset.id = emoteNumber;
			const src = document.getElementById("image");
			src.appendChild(img);
			const form = document.getElementById('picture-form');
			form.insertAdjacentHTML('afterbegin', '<input type="hidden" name="emote['+ name + ']" id="form-emote-' + emoteNumber + '" value="0/250/' + (emoteNumber * 5) + '">');
			emoteNumber++;

        }

		/** make the emotes movable **/
		function sayHi()
		{
			document.getElementById("filler").style.display = "block";
			const emote = this.dataset.id;
			this.style.border = "1px dashed #F00";
			this.style.borderRadius = "2px";
			document.getElementById("filler").dataset.id = emote;
			const src = document.getElementById("emote-control-container");
			src.style.display = 'block';
			src.insertAdjacentHTML('beforeend','<span class="emote-controll" onclick="moveEmoteUp(' + emote + ')"><svg style="height: 15px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M256 0C114.6 0 0 114.6 0 256c0 141.4 114.6 256 256 256s256-114.6 256-256C512 114.6 397.4 0 256 0zM382.6 254.6c-12.5 12.5-32.75 12.5-45.25 0L288 205.3V384c0 17.69-14.33 32-32 32s-32-14.31-32-32V205.3L174.6 254.6c-12.5 12.5-32.75 12.5-45.25 0s-12.5-32.75 0-45.25l103.1-103.1C241.3 97.4 251.1 96 256 96c4.881 0 14.65 1.391 22.65 9.398l103.1 103.1C395.1 221.9 395.1 242.1 382.6 254.6z"/></svg></span><br>');
			src.insertAdjacentHTML('beforeend','<span class="emote-controll" onclick="moveEmoteLeft(' + emote + ')"><svg style="height: 15px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M256 0C114.6 0 0 114.6 0 256c0 141.4 114.6 256 256 256s256-114.6 256-256C512 114.6 397.4 0 256 0zM384 288H205.3l49.38 49.38c12.5 12.5 12.5 32.75 0 45.25s-32.75 12.5-45.25 0L105.4 278.6C97.4 270.7 96 260.9 96 256c0-4.883 1.391-14.66 9.398-22.65l103.1-103.1c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L205.3 224H384c17.69 0 32 14.33 32 32S401.7 288 384 288z"/></svg></span> ');
			src.insertAdjacentHTML('beforeend','<span class="emote-controll" onclick="moveEmoteDown(' + emote + ')"><svg style="height: 15px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M256 0C114.6 0 0 114.6 0 256c0 141.4 114.6 256 256 256s256-114.6 256-256C512 114.6 397.4 0 256 0zM382.6 302.6l-103.1 103.1C270.7 414.6 260.9 416 256 416c-4.881 0-14.65-1.391-22.65-9.398L129.4 302.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L224 306.8V128c0-17.69 14.33-32 32-32s32 14.31 32 32v178.8l49.38-49.38c12.5-12.5 32.75-12.5 45.25 0S395.1 290.1 382.6 302.6z"/></svg></span> ');
			src.insertAdjacentHTML('beforeend','<span class="emote-controll" onclick="moveEmoteRight(' + emote + ')"><svg style="height: 15px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M256 0C114.6 0 0 114.6 0 256c0 141.4 114.6 256 256 256s256-114.6 256-256C512 114.6 397.4 0 256 0zM406.6 278.6l-103.1 103.1c-12.5 12.5-32.75 12.5-45.25 0s-12.5-32.75 0-45.25L306.8 288H128C110.3 288 96 273.7 96 256s14.31-32 32-32h178.8l-49.38-49.38c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l103.1 103.1C414.6 241.3 416 251.1 416 256C416 260.9 414.6 270.7 406.6 278.6z"/></svg></span><br>');
			src.insertAdjacentHTML('beforeend','<span class="emote-controll" onclick="moveEmoteLayerUp(' + emote + ')"><svg style="height: 15px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M544 416h-223.1c-17.67 0-32 14.33-32 32s14.33 32 32 32H544c17.67 0 32-14.33 32-32S561.7 416 544 416zM320 96h32c17.67 0 31.1-14.33 31.1-32s-14.33-32-31.1-32h-32c-17.67 0-32 14.33-32 32S302.3 96 320 96zM320 224H416c17.67 0 32-14.33 32-32s-14.33-32-32-32h-95.1c-17.67 0-32 14.33-32 32S302.3 224 320 224zM320 352H480c17.67 0 32-14.33 32-32s-14.33-32-32-32h-159.1c-17.67 0-32 14.33-32 32S302.3 352 320 352zM151.6 41.95c-12.12-13.26-35.06-13.26-47.19 0l-87.1 96.09C4.475 151.1 5.35 171.4 18.38 183.3c6.141 5.629 13.89 8.414 21.61 8.414c8.672 0 17.3-3.504 23.61-10.39L96 145.9v302C96 465.7 110.3 480 128 480s32-14.33 32-32.03V145.9L192.4 181.3C204.4 194.3 224.6 195.3 237.6 183.3c13.03-11.95 13.9-32.22 1.969-45.27L151.6 41.95z"/></svg></span><br>');
			src.insertAdjacentHTML('beforeend','<span class="emote-controll" onclick="moveEmoteLayerDown(' + emote + ')"><svg style="height: 15px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M320 224H416c17.67 0 32-14.33 32-32s-14.33-32-32-32h-95.1c-17.67 0-32 14.33-32 32S302.3 224 320 224zM320 352H480c17.67 0 32-14.33 32-32s-14.33-32-32-32h-159.1c-17.67 0-32 14.33-32 32S302.3 352 320 352zM320 96h32c17.67 0 31.1-14.33 31.1-32s-14.33-32-31.1-32h-32c-17.67 0-32 14.33-32 32S302.3 96 320 96zM544 416h-223.1c-17.67 0-32 14.33-32 32s14.33 32 32 32H544c17.67 0 32-14.33 32-32S561.7 416 544 416zM192.4 330.7L160 366.1V64.03C160 46.33 145.7 32 128 32S96 46.33 96 64.03v302L63.6 330.7c-6.312-6.883-14.94-10.38-23.61-10.38c-7.719 0-15.47 2.781-21.61 8.414c-13.03 11.95-13.9 32.22-1.969 45.27l87.1 96.09c12.12 13.26 35.06 13.26 47.19 0l87.1-96.09c11.94-13.05 11.06-33.31-1.969-45.27C224.6 316.8 204.4 317.7 192.4 330.7z"/></svg></span><br>');
			src.insertAdjacentHTML('beforeend','<span class="emote-controll" onclick="deleteEmote(' + emote + ')"><svg style="height: 15px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69zM31.1 128H416V448C416 483.3 387.3 512 352 512H95.1C60.65 512 31.1 483.3 31.1 448V128zM111.1 208V432C111.1 440.8 119.2 448 127.1 448C136.8 448 143.1 440.8 143.1 432V208C143.1 199.2 136.8 192 127.1 192C119.2 192 111.1 199.2 111.1 208zM207.1 208V432C207.1 440.8 215.2 448 223.1 448C232.8 448 240 440.8 240 432V208C240 199.2 232.8 192 223.1 192C215.2 192 207.1 199.2 207.1 208zM304 208V432C304 440.8 311.2 448 320 448C328.8 448 336 440.8 336 432V208C336 199.2 328.8 192 320 192C311.2 192 304 199.2 304 208z"/></svg></span>');
		}

		function moveEmoteDown(emoteNumber)
		{
			const emote = document.getElementById('emote_' + emoteNumber);
			console.log(emote.style.top);
			emote.style.top = emote.offsetTop + 5 + 'px';
			emote.style.left = emote.style.left ? emote.style.left : '250px';
			emote.style.zIndex = emote.style.zIndex ? emote.style.zIndex : 5;
			adjustFormData(emoteNumber);
		}

		function moveEmoteUp(emoteNumber)
		{
			const emote = document.getElementById('emote_' + emoteNumber);
			console.log(emote.style.top);
			emote.style.top = emote.offsetTop - 5 + 'px';
			emote.style.left = emote.style.left ? emote.style.left : '250px';
			emote.style.zIndex = emote.style.zIndex ? emote.style.zIndex : 5;
			adjustFormData(emoteNumber);
		}

		function moveEmoteLeft(emoteNumber)
		{
			const emote = document.getElementById('emote_' + emoteNumber);
			console.log(emote.style.left);
			emote.style.left = emote.offsetLeft - 5 + 'px';
			emote.style.top = emote.style.top ? emote.style.top : '25px';
			adjustFormData(emoteNumber);
		}

		function moveEmoteRight(emoteNumber)
		{
			const emote = document.getElementById('emote_' + emoteNumber);
			console.log(emote.style.left);
			emote.style.left = emote.offsetLeft + 5 + 'px';
			emote.style.top = emote.style.top ? emote.style.top : '25px';
			adjustFormData(emoteNumber);
		}

		function moveEmoteLayerUp(emoteNumber)
		{
			const emote = document.getElementById('emote_' + emoteNumber);
			emote.style.zIndex = emote.style.zIndex ? parseInt(emote.style.zIndex) + 5 : 10;
			console.log('up:' + emoteNumber);
			adjustFormData(emoteNumber);
		}

		function moveEmoteLayerDown(emoteNumber)
		{
			const emote = document.getElementById('emote_' + emoteNumber);
			emote.style.zIndex = emote.style.zIndex ? parseInt(emote.style.zIndex) - 5 : 5;
			console.log('down: ' + emoteNumber);
			adjustFormData(emoteNumber);
		}

		function deleteEmote(emoteNumber)
		{
			hideFiller();
			document.getElementById('emote_' + emoteNumber).remove();
			console.log('removed: ' + emoteNumber);
			adjustFormData(emoteNumber, true);
		}

		function adjustFormData(emoteNumber, remove = false)
		{
			if (!remove){
				const emoteInput = document.getElementById('form-emote-' + emoteNumber);
				const emote = document.getElementById('emote_' + emoteNumber);

				emoteInput.value = emote.style.top + '/' + emote.style.left + '/' + emote.style.zIndex;
			} else {
				console.log('here')
				document.getElementById('form-emote-' + emoteNumber).remove();
			}
		}

		document.getElementById('file').addEventListener("change", uploadImage, true);

		function uploadImage()
		{
			const file = document.getElementById('file').files[0];
			const img = document.getElementById('tmp-upload');

			if (file) {
				img.src = URL.createObjectURL(file);
			}
		}

		function drawImage()
		{
			const canvas = document.getElementById('canvas');
			let context = canvas.getContext('2d');
			const img = document.getElementById('tmp-upload');
			canvas.width = img.naturalWidth;
			canvas.height = img.naturalHeight;
			context.drawImage(img, 0, 0, img.naturalWidth, img.naturalHeight);
			if (img.naturalWidth >= 1080) {
				canvas.style.width = '1080px';
			} else {
				canvas.style.width = img.naturalWidth;
			}

			document.getElementById('picture').style.display = 'block';
			document.getElementById('camera').style.display = 'none';

			document.getElementsByClassName('camera_container')[0].style.display = 'block';
		}
	</script>
@endsection

