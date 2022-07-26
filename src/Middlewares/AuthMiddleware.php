<?php

namespace Middlewares;


use Controller\CameraController;
use Simfa\Framework\Application;
use Simfa\Framework\Middleware\BaseMiddleware;

class AuthMiddleware extends BaseMiddleware
{
    public function __construct(array $action = [])
    {
	    parent::__construct($action);
    }

	/**
	 * check if user is logged in otherwise redirect to home page
	 */
    public function execute()
    {
        if (Application::isGuest()) {
            if (empty($this->action) || in_array(Application::$APP->controller->action, $this->action)) {
				if (Application::$APP->controller::class == CameraController::class && Application::$APP->controller->action == 'index')
					Application::$APP->session->setFlash('error', 'you must login first, smart ass :-)');
				Application::$APP->response->redirect('/');
            }
        }
    }
}
