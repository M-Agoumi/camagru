<?php


namespace core\Middleware;


use core\Application;
use core\Exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{

    public array $action = [];

    public function __construct(array $action = [])
    {
        $this->action = $action;
    }

    /**
     *
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