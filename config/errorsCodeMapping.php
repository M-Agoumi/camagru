<?php
/**
 * if the error code in this array it will be shown, otherwise it will show only error 500
 * so when a user report an error in production you know exactly what problem he encountered without sabotaging
 * the security of your application with showing error codes
 */
return [
	'1049'      => '0x00100f', // database doesn't exist
	'42S02'     => '0x0010a0', // table not found
];
