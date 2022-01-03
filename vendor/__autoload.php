<?php

set_include_path(dirname(__DIR__) . '/');

spl_autoload_register('autoloader');

function autoloader($class_name) {
	$file_name 	= NULL;
	$root		= get_include_path();
	$class_name = str_replace("\\", "/", $class_name);
	
	/** check if class exists on vendor otherwise check project folders */
	if (file_exists($root . 'vendor/' . $class_name . '.php'))
		$file_name = $root . 'vendor/' . $class_name . '.php';

	if (file_exists($root . 'src/' . $class_name . '.php'))
		$file_name = $root . 'src/' . $class_name . '.php';

	if ($file_name) {
		$file_name = str_replace("\\", "/", $file_name);
		require($file_name);
		return ;
	}
    
	/** keep the script going but note the error in the errors.log */
	$logMessage = "[" . date('Y-m-d H:i:s') . "] error while auto loading a file: [$file_name] not found" . PHP_EOL ;
	$logFile = dirname(__DIR__) . "/runtime/logs/errors.log";
	file_put_contents($logFile, $logMessage , FILE_APPEND | LOCK_EX);
}
