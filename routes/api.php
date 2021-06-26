<?php

use controller\DefaultController;
use core\Application;

$app = Application::$APP;

/**
* all the routes of our application
*/

/** default Controller routes */
$app->router->get('/', [DefaultController::class, 'api'])->name('home.index');