<?php

set_include_path(dirname(__DIR__) . '/');

spl_autoload_register('autoloader');

function autoloader($class_name) {
	/** check if this is a vendor or core class */
	$namespace = strtok($class_name, "\\");

	if ($namespace == 'vendor')
		$file_name = get_include_path() .$class_name . '.php';
	else
		$file_name = get_include_path() . 'src/' .$class_name . '.php';

	$file_name = str_replace("\\", "/", $file_name);

	if (file_exists($file_name))
		require($file_name);
    else
    {
		/** keep the script going but note the error in the errors.log */
        $logMessage = "[" . date('Y-m-d H:i:s') . "] error while auto loading a file: $file_name not found" . PHP_EOL ;
		$logFile = dirname(__DIR__) . "/runtime/logs/errors.log";
	    file_put_contents($logFile, $logMessage , FILE_APPEND | LOCK_EX);
    }
}
