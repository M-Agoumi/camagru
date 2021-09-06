<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   index.php                                         :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/03/16 17:05:43 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/07/17 21:52:45 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

/**
 * coded on php 7.4.3 environment compatible with php 8.0*
 * (*)has not been tested on it yet
 * check if php version is meet
 */

if (version_compare(phpversion(), '7.4.0', '<='))
	die('php version is too old, this scripts require php >=  v7.4');

/**
 * start counting execution time
 */

$start = microtime(true);

/**
 * set cookies to httponly to protect from XSS
 */
ini_set('session.cookie_httponly', 1);

/**
 * set session path to our local app directory in case of permissions issues
 */
// ini_set('session.save_path', '../runtime/session');

/**
 * Getting global setting from the env file
 * enable error reporting if the env is dev
 */

// check if the .env exist
if (!file_exists(__DIR__ . "/../.env"))
	die('.env file not found, please set it up first (use the .env.example template)');

$config = parse_ini_file(__DIR__ . "/../.env");
if (isset($config['env']) && $config['env'] === 'dev'){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL ^ E_DEPRECATED);
} else {
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(0);
}

/**
 * require our autoloader
 */

require_once "../__autoload.php";

use core\Application;

/**
 * creating a new instance of the Application class
 */
$app = new Application(dirname(__DIR__));

/**
 * register shutdown function
 */

function shutdown($start)
{
	$time_elapsed_secs = microtime(true) - $start;
	$load = sys_getloadavg();
	
	if (Application::$ENV['env'] == 'dev') {
		echo '<script>';
		echo 'console.log("execution time ' . round($time_elapsed_secs, 3) . 's");' . PHP_EOL;
		echo 'console.log("Peak memory: ' . round(memory_get_peak_usage() / 1024) . 'KB");';
		echo 'console.log("CPU Usage: ' . $load[0] . '%");';
		echo '</script>', PHP_EOL;
	}
}

register_shutdown_function('shutdown', $start);

/**
 * run our application
 */
$app->run();
