<?php

use controller\ApiController;
use core\Application;
use core\Router;

$app = Application::$APP;

/**
* all the routes of our application
*/

Router::post('posts', [ApiController::class, 'posts']);
