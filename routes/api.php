<?php

use Controller\ApiController;
use Simfa\Framework\Application;
use Simfa\Framework\Router;

$app = Application::$APP;

/**
* all the routes of our application
*/

Router::post('posts', [ApiController::class, 'posts']);
