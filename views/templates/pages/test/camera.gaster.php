@layout('main')
@section('title'){{ title }}@endsection
@section('head')
	<link rel="stylesheet" href="assets/css/camera.css" />
@endsection
@section('content')
	<h1>Upload your picture</h1>
	<div class="button-container">
		<button onclick="getWebCam()">camera</button>
		<button onclick="getUploadForm()">upload</button>
	</div>
	<div class="camera_upload_container">
		<label for="files">chose a file</label>
		<input type="file" class="multiChoiceSelectButton" name="file" id="file" accept="image/*">
		<input type="hidden" value="@csrf" id="csrf_camera" />
		<input type="button" id="btn_upload_file"
			   value="Upload"
			   class="multiChoiceSelectButton"
			   onclick="prepareImage();" > <!-- onclick="uploadImage(); -->
		<img id="tmp-upload" style="display: none" />
		<div id="upload_msg">

		</div>
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
			<button onclick="saveCamera()">confirm</button>
			<button onclick="retake()">retake</button>
		</div>
	</div>

	<div class="image-container">
		<div class="camera-image-wrapper">
			<img id='image-holder' class="image-holder" alt="">
			<div class="emotes" id="emotes-container">
			</div>
		</div>
		<button onclick="save()" class='picture-save-button'>save</button>
	</div>

	<div class="emotes-panel emotes-panel-left">
		<div class="emotes-panel-container">
			<div class="emote-show-toggle">
				<i class="fas fa-arrow-alt-circle-left" onclick="toggleEmotePanel(this, 0)"></i>
			</div>
			<div class="emote-control">
				<h1>control</h1>
				<span onclick="updateEmote(1)"><i class="fas fa-plus-circle"></i></span>
				<span onclick="updateEmote(2)"><i class="fas fa-minus-circle"></i></span>
				<br>
				<span onclick="updateEmote(3)"><i class="fas fa-arrow-circle-up"></i></span>
				<br>
				<span onclick="updateEmote(6)"><i class="fas fa-arrow-circle-left"></i></span>
				<span onclick="updateEmote(5)"><i class="fas fa-arrow-circle-right"></i></span>
				<br>
				<span onclick="updateEmote(4)"><i class="fas fa-arrow-circle-down"></i></span>
				<br>
				<span onclick="updateEmote(7)"><i class="fas fa-sort-amount-up-alt"></i></span>
				<span onclick="updateEmote(8)"><i class="fas fa-sort-amount-down-alt"></i></span>
				<br>
				<span onclick="updateEmote(9)"><i class="fas fa-trash-alt"></i></span>
			</div>
		</div>
	</div>

	<div class="emotes-panel emotes-panel-right">
		<div class="emotes-panel-container">
			<div class="emotes-panel-actions">
				<span class="emote-show-toggle">
					<i class="fas fa-arrow-alt-circle-left" onclick="toggleEmotePanel(this, 1)"></i>
				</span>
			</div>
			<div class="emotes-panel-content">
				<h1>Previous Edits</h1>
				<div class="templates">
					<h1>previous edits</h1>
					<?php foreach ($templates as $template):?>
						<img src="/assets/img/transparent.jpg" data-image='<?=$template['content']?>' onclick="applyTemplate(this)" alt="template"/>
					<?php endforeach;?>

				</div>
				<div class="emotes">
					<h1>Emotes</h1>
<!--					<img src="assets/img/emotes/harambe.png" data-image='harambe' onclick="addEmote(this)">-->
<!--					<img src="assets/img/emotes/heart.png" data-image='heart' onclick="addEmote(this)">-->
					<?php foreach ($emotes as $emote):?>
						<img src="/assets/img/emotes/<?=$emote['file']?>" data-image='<?=$emote['name']?>' onclick="addEmote(this)" alt="<?=$emote['name']?>"/>
					<?php endforeach;?>

				</div>
				<div class="borders">
					<h1>borders</h1>
					<p class="no-border-image" onclick="addBorder(false)">no border</p>
<!--					<img src="/assets/img/borders/border-1.png" data-border='border-1' onclick="addBorder(this)"/>-->
<!--					<img src="/assets/img/borders/border-2.png" data-border='border-2' onclick="addBorder(this)"/>-->
<!--					<img src="/assets/img/borders/border-3.png" data-border='border-3' onclick="addBorder(this)"/>-->
					<?php foreach ($borders as $emote):?>
						<img src="/assets/img/borders/<?=$emote['file']?>" data-image='<?=$emote['name']?>' onclick="addBorder(this)" alt="<?=$emote['name']?>"/>
					<?php endforeach;?>
				</div>
			</div>
		</div>
	</div>

	<script>
		const file_max_size = <?= \Simfa\Framework\Application::getAppConfig('post', 'max_file_size') ?>;
		const success_link = '<?= \Simfa\Framework\Application::getEnvValue('URL') . path('camera.save') ?>';
	</script>
	<script>


		/**
		 * image from camera functions
		 */

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

		function saveCamera()
		{
			const img = canvas.toDataURL("image/jpeg");
			console.log(img);
			const formData = new FormData();

			formData.append("file", img);

			const xhr = new XMLHttpRequest();
			// Set POST method and ajax file path

			xhr.open("POST", "/camera/picture/camera", true);
			xhr.setRequestHeader('CSRF', document.getElementById('csrf_camera').value)

			// call on request changes state
			xhr.onreadystatechange = function () {
				if (this.readyState === 4) {
					/**
					 * @type object {status:boolean, picture: string, message:string}
					 */
					let {picture, status, message} = JSON.parse(this.responseText);

					if (status) {
						activeImage = picture;
						imageHolder.src = '/tmp/' + picture;
						imageHolder.onload = initImageObject;
						document.getElementsByClassName('camera_upload_container')[0].style.display = 'none';
						document.getElementById('picture').style.display = 'none';
						document.getElementsByClassName('picture-save-button')[0].style.display = 'block';
						imageInitiated = true;
					} else {
						document.getElementById('msg').innerText = message;
					}
				}
			}
			// Send request with data
			xhr.send(formData);
		}

		function retake()
		{
			console.log('we retaking');
		}

		/**
		 * end image from camera functions
		 */

		/**
		 * Image upload functions
		 * @type {string[]}
		 */
		allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

		/** watch changes on our input to init ajax calls **/
		document.getElementById('file').addEventListener("change", prepareImage, true);

		/** the image holding the current working on image **/
		const imageHolder = document.getElementById('image-holder');

		/** active emote for changing **/
		let activeEmote = null;
		/** path to our image **/
		let activeImage = null;
		/** make sure we don't add borders and emotes to nothing **/
		let imageInitiated = false;

		function prepareImage()
		{
			// remove any previous message
			document.getElementById('upload_msg').innerText = '';

			const file = document.getElementById('file').files[0];
			const fileExt = file ? file.name.split('.').pop() : false;

			if (fileExt && allowed_extensions.includes(fileExt)) {
				if (fileExt === 'gif')
					uploadAnimated();
				else
					uploadPicture();
			} else
				document.getElementById('upload_msg').innerText = 'file extension is not valid';

		}

		function uploadAnimated() {
			const formData = new FormData();
			const files = document.getElementById("file").files;

			formData.append("file", files[0]);

			const xhr = new XMLHttpRequest();
			// Set POST method and ajax file path
			if (files[0].size <= file_max_size) {

				xhr.open("POST", "/camera/animated", true);
				xhr.setRequestHeader('CSRF', document.getElementById('csrf_camera').value)

				// call on request changes state
				xhr.onreadystatechange = function () {
					if (this.readyState === 4) {
						let response = JSON.parse(this.responseText);

						if (response.status)
							window.location.href = '/camera/share';
						console.log(response);
					}
				};

				// Send request with data
				xhr.send(formData);
			} else {
				document.getElementById('upload_msg').innerText = 'file too large';
			}
		}

		function uploadPicture() {
			const formData = new FormData();
			const files = document.getElementById("file").files;

			formData.append("file", files[0]);

			const xhr = new XMLHttpRequest();
			// Set POST method and ajax file path
			if (files[0].size <= file_max_size) {

				xhr.open("POST", "/camera/picture", true);
				xhr.setRequestHeader('CSRF', document.getElementById('csrf_camera').value)

				// call on request changes state
				xhr.onreadystatechange = function () {
					if (this.readyState === 4) {
						/**
						 * @type object {status:boolean, picture: string, message:string}
						 */
						let {picture, status, message} = JSON.parse(this.responseText);

						if (status) {
							activeImage = picture;
							imageHolder.src = '/tmp/' + picture;
							imageHolder.onload = initImageObject;
							document.getElementsByClassName('camera_upload_container')[0].style.display = 'none';
							document.getElementsByClassName('picture-save-button')[0].style.display = 'block';
							imageInitiated = true;
						} else {
							document.getElementById('msg').innerText = message;
						}
					}
				};

				// Send request with data
				xhr.send(formData);
			}
		}

		/** save image to the server **/
		function save() {
			let data = {picture: activeImage,'data': imageDetails}

			const xhr = new XMLHttpRequest();
			// Set POST method and ajax file path

			xhr.open("POST", "/camera/create", true);
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xhr.setRequestHeader('CSRF', document.getElementById('csrf_camera').value)

			// call on request changes state
			xhr.onreadystatechange = function () {
				if (this.readyState === 4) {
					let response = JSON.parse(this.responseText);
					if (response.status)
						window.location.href = success_link;
				}
			};

			// Send request with data
			xhr.send('data=' + JSON.stringify(data));
		}


		/**
		 * end image upload functions
		 */

		/**
		 * image preprocessing section
		 */

		let imageDetails = {
			width: 0,
			height: 0,
			realWidth: 0,
			realHeight: 0,
			border: false,
			emotes: []
		};

		function initImageObject()
		{
			imageDetails.realWidth = imageHolder.naturalWidth;
			imageDetails.width = imageHolder.width;
			imageDetails.realHeight = imageHolder.naturalHeight;
			imageDetails.height = imageHolder.height;
		}

		function applyTemplate(template) {
			if (!imageInitiated)
				return false;
			// console.log(imageDetails);
			let templateHolder = JSON.parse(template.getAttribute('data-image'));
			for (let i in templateHolder.emotes)
				copyEmote(templateHolder.emotes[i]);
			updateEmotesContainer();
			updateEmotes();
		}

		function copyEmote(emoteToCopy)
		{
			if (!emoteToCopy.active)
				return ;
			let newEmote = {
				active: true,
				height: emoteToCopy.height,
				width: emoteToCopy.width,
				left: emoteToCopy.left,
				top: emoteToCopy.top,
				zIndex: emoteToCopy.zIndex,
				picture: emoteToCopy.picture,
				emote: document.querySelector('[data-image="' + emoteToCopy.picture + '"]')
			}
			imageDetails.emotes.push(newEmote);
		}

		/** add border from panel to image **/
		function addBorder(border)
		{
			if (!imageInitiated)
				return false;
			/** check if image has been updated first else do nothing **/
			if (imageHolder.src) {
				if (border) {
					let newImage = new Image();
					newImage.src = border.src;
					newImage.width = imageHolder.width;
					newImage.height = imageHolder.height;
					newImage.style.zIndex = '5';
					let tag = border.getAttribute('data-border');
					imageDetails.border = {border:tag, picture: newImage};
				} else {
					imageDetails.border = false;
				}
				updateEmotesContainer();
				updateEmotes();
			}
		}

		/** add new emote to the image **/
		function addEmote(emote)
		{
			if (!imageInitiated)
				return false;
			let newEmote = new Image();

			newEmote.src = emote.src;
			newEmote.width = emote.width;
			newEmote.height = emote.height;
			newEmote.style.zIndex = ((imageDetails.emotes.length + 1) * 10).toString();
			newEmote.style.position = 'absolute';
			newEmote.style.top = imageDetails.height / 2 + 'px';
			newEmote.style.left = imageDetails.width / 2 + 'px';
			newEmote.classList.add('movable-emote');
			let tag = emote.getAttribute('data-image');
			newEmote.setAttribute('data-image', tag);
			newEmote.setAttribute('data-index', imageDetails.emotes.length + '');
			let myEmote = {
				emote: newEmote,
				top: newEmote.style.top,
				left: newEmote.style.left,
				width: newEmote.width,
				height: newEmote.height,
				zIndex: newEmote.style.zIndex,
				active: true,
				picture: tag
			};
			imageDetails.emotes.push(myEmote);
			updateEmotesContainer();
			updateEmotes();
		}

		/** update emotes list **/
		function updateEmotes()
		{
			let elements = document.getElementsByClassName("movable-emote");

			const moveSpecialEmote = function () {
				if (activeEmote)
					activeEmote.style.border = 'none';
				if (this !== activeEmote) {
					this.style.border = '1px dashed red';
					activeEmote = this;
				} else {
					activeEmote = null;
				}
			};

			for (let i = 0; i < elements.length; i++) {
				elements[i].addEventListener('click', moveSpecialEmote, false);
			}
		}

		/** update emote position/size **/
		function updateEmote(type)
		{
			if (!activeEmote){
				alert('please select an emote first');
				return null;
			}
			let index = activeEmote.getAttribute('data-index');
			switch (type) {
				case 1:
					activeEmote.width = activeEmote.width + 5;
					activeEmote.height = activeEmote.height + 5;
					imageDetails.emotes[index].width = activeEmote.width;
					imageDetails.emotes[index].height = activeEmote.height;
					break;
				case 2:
					activeEmote.width = activeEmote.width - 5;
					activeEmote.height = activeEmote.height - 5;
					imageDetails.emotes[index].width = activeEmote.width;
					imageDetails.emotes[index].height = activeEmote.height;
					break;
				case 3:
					activeEmote.style.top = parseInt(activeEmote.style.top,10) - 5 + 'px';
					if (parseInt(activeEmote.style.top,10) < 0)
						activeEmote.style.top = '0';
					imageDetails.emotes[index].top = activeEmote.style.top;
					break;
				case 4:
					activeEmote.style.top = parseInt(activeEmote.style.top,10) + 5 + 'px';
					if (parseInt(activeEmote.style.top,10) > imageDetails.height - activeEmote.height)
						activeEmote.style.top = imageDetails.height - activeEmote.height + 'px';
					imageDetails.emotes[index].top = activeEmote.style.top;
					break;
				case 5:
					activeEmote.style.left = parseInt(activeEmote.style.left,10) + 5 + 'px';
					if (parseInt(activeEmote.style.left,10) > imageDetails.width - activeEmote.width)
						activeEmote.style.left = imageDetails.width - activeEmote.width + 'px';
					imageDetails.emotes[index].left = activeEmote.style.left;
					break;
				case 6:
					activeEmote.style.left = parseInt(activeEmote.style.left,10) - 5 + 'px';
					if (parseInt(activeEmote.style.left,10) < 0)
						activeEmote.style.left = '0';
					imageDetails.emotes[index].left = activeEmote.style.left;
					break;
				case 7:
					activeEmote.style.zIndex = parseInt(activeEmote.style.zIndex) +  10 + '';
					imageDetails.emotes[index].zIndex = activeEmote.style.zIndex;
					break;
				case 8:
					activeEmote.style.zIndex = parseInt(activeEmote.style.zIndex) - 10 + '';
					if (parseInt(activeEmote.style.zIndex) < 0)
						activeEmote.style.zIndex = '0';
					imageDetails.emotes[index].zIndex = activeEmote.style.zIndex;
					break;
				case 9:
					activeEmote.remove();
					activeEmote = null;
					imageDetails.emotes[index].active = false;
					break;
			}
		}

		/** update all the components after every change **/
		function updateEmotesContainer()
		{
			const emotesContainer = document.getElementById('emotes-container');
			emotesContainer.innerHTML = '';
			if (imageDetails.border) {
				emotesContainer.append(imageDetails.border.picture);
			}
			if (imageDetails.emotes.length) {
				for (let i in imageDetails.emotes) {
					if (imageDetails.emotes[i].active)
					emotesContainer.append(imageDetails.emotes[i].emote);
				}
			}
		}

		/**
		 * end image preprocessing section
		 */


		/**
		 * interactive functions (DOM manipulators)
		 */

		function toggleEmotePanel(button, panel) {
			button.classList.toggle('rotated');
			if (!panel) {
				document.getElementsByClassName('emotes-panel')[panel].classList.toggle('emotes-control-panel-show');
			} else {
				document.getElementsByClassName('emotes-panel')[panel].classList.toggle('emotes-panel-show');
			}
		}

		function getUploadForm()
		{
			document.getElementsByClassName('button-container')[0].style.display = 'none';
			document.getElementsByClassName('camera_upload_container')[0].style.display = 'block';
		}
		/** remove after testing **/
		// getUploadForm();
		//
		// imageHolder.src = '/tmp/felix-uresti-F5uQIJRoyb0-unsplash.jpg';
		// imageHolder.onload = initImageObject;
	</script>
@endsection

