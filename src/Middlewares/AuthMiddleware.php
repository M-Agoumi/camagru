<?php


namespace Middlewares;


use core\Application;
use core\Exception\ExpiredException;
use core\Middleware\BaseMiddleware;

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
				Application::$APP->response->redirect('/');
            }
        }
    }
}
