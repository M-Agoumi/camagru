<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   index.php                                         :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/03/16 17:05:43 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/03/17 09:13:14 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

/**
 * Getting global setting from the env file
 * enable error reporting if the env is dev
 */

$config = parse_ini_file(__DIR__ ."/../.env");
if (isset($config['env']) && $config['env'] === 'dev')
	error_reporting(E_ALL ^ E_DEPRECATED);
else
	error_reporting(0);

/**
 * require our app class the heart of our application
 */
/**
 * require our autoloader
 */

$autoloadPath = __DIR__ . "/../__autoload.php";

if (file_exists($autoloadPath))
    require_once $autoloadPath;
else
    die("Failed to require the __autoloader: $autoloadPath\n");

use core\Application;

/**
 * creating a new instance of the Application class
 */

$app = New Application(dirname(__DIR__));

/**
 * require the routes of our application
 */


/**
 * run our application
 */
$app->db->applyMigrations();
