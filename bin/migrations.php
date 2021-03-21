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
require_once __DIR__ . "/../core/Application.php";

/**
 * creating a new instance of the Application class
 */

$app = New Application(dirname(__DIR__));


/**
 * include our controllers so we can send their methods
 */

include_once $app::$ROOT_DIR . "/controller/DefaultController.php";
include_once $app::$ROOT_DIR . "/controller/AuthController.php";


/**
 * run our application
 */
$app->db->applyMigrataions();
