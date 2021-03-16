<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        ::::::::            */
/*   index.php                                          :+:    :+:            */
/*                                                     +:+                    */
/*   By: magoumi <magoumi@student.1337.ma>            +#+                     */
/*                                                   +#+                      */
/*   Created: 2021/03/16 17:05:43 by null          #+#    #+#                 */
/*   Updated: 2021/03/16 17:05:43 by null          ########   odam.nl         */
/*                                                                            */
/* ************************************************************************** */


/*
 * Getting global setting from the env file
 * enable error reporting if the env is dev
 */
$config = parse_ini_file(__DIR__ ."/../.env");
if (isset($config['env']) && $config['env'] === 'dev')
	error_reporting(E_ALL ^ E_DEPRECATED);

/*
 * require our app class the heart of our application
 */
require_once __DIR__ . "/../core/Application.php";

/*
 * creating a new instance of the Application class
 */
$app = New Application();

$app->router->get('/', function (){
	return "Hello world";
});

$app->run();
