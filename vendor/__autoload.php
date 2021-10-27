<?php

set_include_path(__DIR__ . '/src/');

spl_autoload_register('autoloader');

function autoloader($class_name) {
	$file_name = get_include_path() .$class_name . '.php';
	$file_name = str_replace("\\", "/", $file_name);
 
	if (file_exists($file_name))
		require($file_name);
    else
        die ("fetal error while auto loading a file: not found: " . $file_name);
}
