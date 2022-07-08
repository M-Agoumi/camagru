let selectedImage   = null;
let selectedDiv     = null;

function showControl()
{
	document.getElementsByClassName('profile-form')[0].style.display = 'block';
	document.getElementsByClassName('black-screen')[0].style.display = 'block';
}

function hideControl()
{
	document.getElementsByClassName('black-screen')[0].style.display = 'none';
	document.getElementsByClassName('profile-form')[0].style.display = 'none';
}

function selectCover()
{
	document.getElementById('first-action').style.display = 'none';
	document.getElementById('second-action').style.display = 'block';
}

function selectDp()
{
	document.getElementById('first-action').style.display = 'none';
	document.getElementsByClassName('profile_images')[0].style.display = 'block';
}

function selectDefinedImages()
{
	document.getElementById('second-action').style.display = 'none';
	const imgDiv = document.getElementsByClassName('container-relative')[0];
	getImagesCollection(imgDiv);

	imgDiv.style.display = 'block';
}

function getImagesCollection()
{
	const imageDiv = document.getElementsByClassName('images-collection')[0];
	if (!imageDiv.innerHTML) {
		const xhr = new XMLHttpRequest();
		let data = null;
		xhr.open('post','/api/cover/collection');

		xhr.onload = () => {
			data = JSON.parse(xhr.responseText);
			if (data.length) {
				for (let i in data) {
					let image = '<div data-image="' + data[i]['image'] + '" class="image-collection-child" onclick="updateCover(this)">';
					image += '<img height="250px" src="/uploads/cover/' + data[i]['image'] + '" alt="' + data[i]['name'] + '"><br>';
					image += data[i]['name'] + '</div>';
					imageDiv.insertAdjacentHTML('beforeend', image);
				}
			}
		}

		xhr.send();
	}
}

function selectCustomImage()
{
	document.getElementById('second-action').style.display = 'none';
	document.getElementsByClassName('custom_images')[0].style.display = 'block';
}

function backToSecondAction()
{
	document.getElementById('second-action').style.display = 'block';
	document.getElementsByClassName('custom_images')[0].style.display = 'none';
	document.getElementsByClassName('container-relative')[0].style.display = 'none';
}

function backHome()
{
	document.getElementById('second-action').style.display = 'none';
	document.getElementsByClassName('profile_images')[0].style.display = 'none';
	document.getElementById('first-action').style.display = 'block';
}

function updateCover(div)
{
	if (selectedDiv)
		selectedDiv.classList.remove('selected-image');
	selectedDiv = div;
	selectedImage = div.getAttribute('data-image');
	div.classList.add('selected-image');
}

function reloadPage() {
	setTimeout(function () { location.reload(); }, 5000);
}

function uploadFile(cover = false) {
	if (!cover) {
		var files = document.getElementById("file").files;
	} else {
		var files = document.getElementById("file_id").files;
	}

	console.log(files);
	if(files.length > 0 ){
		const formData = new FormData();
		formData.append('type', '0');
		formData.append("file", files[0]);

		const xhr = new XMLHttpRequest();
		// Set POST method and ajax file path
		if (files[0].size <= file_max_size) {

			xhr.open("POST", "/api/user/background", true);
			xhr.setRequestHeader('CSRF', document.getElementById('csrf_profile').value)

			// call on request changes state
			xhr.onreadystatechange = function () {
				if (this.readyState === 4) {
					const response = JSON.parse(this.responseText);
					if (response.status === true) {
						document.getElementById('message').innerText = 'cover image has been updated';
						document.getElementById('message').style.color = 'var(--main-colo)';
						reloadPage();
					} else {
						document.getElementById('message').innerText = response.message;
						document.getElementById('message').style.color = '#F00';
					}
				}
			};

			// Send request with data
			xhr.send(formData);
		} else {
			document.getElementById('message').innerText = "fie size must be less than " + (file_max_size / 1048576) + 'MB';
			document.getElementById('message').style.color = '#F00';
		}

	}else{
		document.getElementById('message').innerText = "Please select a file";
		document.getElementById('message').style.color = '#F00';
	}
}

function submitCover()
{
	if (selectedImage) {
		const xhr = new XMLHttpRequest();
		xhr.open('post','/api/user/background');
		let token =  document.getElementById('csrf_profile').value;

		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

		xhr.onload = () => {
			let data = JSON.parse(xhr.responseText);

			document.getElementById('message').innerText = data.message;
			if (data.status == false) {
				document.getElementById('message').style.color = '#F00';
			} else {
				document.getElementById('message').style.color = 'var(--main-colo)';
				reloadPage();
			}
		}

		xhr.send('type=1&image=' + selectedImage + '&__csrf='+token);
	} else {
		const message = document.getElementById('message');
		message.style.color = '#F00';
		message.innerText = 'please select a cover first';
	}
}

function uploadProfilePic() {
	const files = document.getElementById("dp-file").files;

	if(files.length > 0 ){

		const formData = new FormData();
		formData.append("file", files[0]);
		formData.append('__csrf', document.getElementById('csrf_profile').value);

		const xhr = new XMLHttpRequest();

		// Set POST method and ajax file path

		xhr.open("POST", "/api/user/dp", true);
		xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

		// call on request changes state
		xhr.onreadystatechange = function() {
			if (this.readyState === 4 && this.status === 200) {

				console.log(this.responseText);
				const response = JSON.parse(this.responseText);
				if(response.status == 1){
					document.getElementById('message').innerText = 'cover image has been updated';
					document.getElementById('message').style.color = 'var(--main-colo)';
					reloadPage();
				}else{
					document.getElementById('message').innerText = response.message;
					document.getElementById('message').style.color = '#F00';
				}
			}
		};

		// Send request with data
		xhr.send(formData);

	}else{
		document.getElementById('message').innerText = "Please select a file";
		document.getElementById('message').style.color = '#F00';
	}
}

function deleteCover()
{
	const xhr = new XMLHttpRequest();
	xhr.open('post','/api/user/remove/background');

	xhr.onload = () => {
		let data = JSON.parse(xhr.responseText);

		document.getElementById('message').innerText = data.message;
		if (data.status == false) {
			document.getElementById('message').style.color = '#F00';
		} else {
			document.getElementById('message').style.color = 'var(--main-colo)';
			reloadPage();
		}
		console.log(data);
	}

	xhr.send('type=1&image=' + selectedImage);
}

function deleteDp()
{
	const xhr = new XMLHttpRequest();
	xhr.open('post','/api/user/remove/dp');

	xhr.onload = () => {
		let data = JSON.parse(xhr.responseText);

		document.getElementById('message').innerText = data.message;
		if (data.status == false) {
			document.getElementById('message').style.color = '#F00';
		} else {
			document.getElementById('message').style.color = 'var(--main-colo)';
			reloadPage();
		}
		console.log(data);
	}

	xhr.send('type=1&image=' + selectedImage);
}
