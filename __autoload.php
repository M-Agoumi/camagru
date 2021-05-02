<?php

set_include_path(__DIR__.'/src/');

spl_autoload_register('autoloader');

function autoloader($class_name) {
	
	// echo "class name : $class_name <br>";
    
    if ($class_name == 'AuthController')
    	$class_name = 'controller/AuthController';
    
    //    echo "Include path: " . get_include_path() . "<br>";
    
	$file_name = get_include_path() .$class_name . '.php';

	//    echo "File name : $file_name <br>";
 
	$file_name = str_replace("\\", "/", $file_name);
 
	if (file_exists($file_name)) {

        // echo "class include ".$class_name . "<br>";
  
		require($file_name);
    } else {
        echo "file not found: " . $file_name;
    }

	//    echo "<br><br>";
}