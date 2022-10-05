<?php
return [
	'view' => ['cache' => true], // if disabled, it became harder to debug in case of a problem in the view
	'session' => [
		'salt' => 'ankhs', // salt for the key of flash message to avoid getting overwritten by a dev
		'csrf' => 90 // csrf tokens expiration time in minutes
	],
	'post' => [
		'max_file_size' => 19145728, // max file size for the uploaded image 1008945 3145728
		'allowed_ext' => ['png', 'jpg', 'jpeg', 'gif'] // allowed extension for uploaded posts
	]
];
