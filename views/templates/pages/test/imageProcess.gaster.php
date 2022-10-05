<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
</head>
<body style="text-align: right">
	<button onclick="sendData()">send</button><br>
	<img id='myImage' src="/tmp/agoumiTesting.png" alt="testing" style="max-width: 1600px; max-height: 720px "/>
	<script>
		let mockData = {
			picture: 'tamara-schipchinskaya-UY3VQRXTgdg-unsplash.jpg',
			data: {
				border: {border: "border-1"},
				emotes: [
					{
						active: true,
						height: 70,
						left: "270px",
						picture: "heart",
						top: "315px",
						width: 70,
						zIndex: "10",
					}, {
						active: true,
						height: 100,
						left: "380px",
						picture: "cat",
						top: "620px",
						width: 100,
						zIndex: "20",
					}
				],
				height: 720,
				realHeight: 5513,
				realWidth: 3675,
				width: 480
			}
		}

		function sendData()
		{
			const xhr = new XMLHttpRequest();
			// Set POST method and ajax file path
			xhr.open("POST", "/camera/create", true);
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

			// call on request changes state
			xhr.onreadystatechange = function () {
				if (this.readyState === 4) {
					/**
					 * @type object {status:boolean, picture: string, message:string}
					 */
					// let {picture, status, message} = JSON.parse(this.responseText);
					//
					// if (status) {
					// 	imageHolder.src = '/tmp/' + picture;
					// 	imageHolder.onload = initImageObject;
					// 	document.getElementsByClassName('camera_upload_container')[0].style.display = 'none';
					// } else {
					// 	document.getElementById('msg').innerText = message;
					// }
					// document.getElementById("myImage").src = this.responseText;
					updateImage();
					console.log(this.responseText);
				}
			};

			// Send request with data
			xhr.send('ext=jpg&data=' + JSON.stringify(mockData));

			console.log(mockData);
		}

		function updateImage()
		{
			let source = '/tmp/agoumiTesting.png',
				timestamp = (new Date()).getTime();
			document.getElementById("myImage").src = source + '?_=' + timestamp;
		}
	</script>
</body>
</html>
