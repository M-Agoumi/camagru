<?php


namespace Middlewares;


use core\Application;
use core\Exception\ForbiddenException;
use core\Middleware\BaseMiddleware;

class AuthMiddleware extends BaseMiddleware
{
    public function __construct(array $action = [])
    {
	    parent::__construct($action);
    }

    /**
     * @throws ForbiddenException
     */
    public function execute()
    {
        if (Application::isGuest()) {
            if (empty($this->action) || in_array(Application::$APP->controller->action, $this->action)) {
                // throw new ForbiddenException();
				Application::$APP->response->redirect('/');
            }
        }
    }
}
