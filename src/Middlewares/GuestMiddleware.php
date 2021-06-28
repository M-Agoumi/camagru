<?php


namespace Middlewares;


use core\Application;

class GuestMiddleware extends \core\Middleware\BaseMiddleware
{
	public function __construct(array $action = [])
	{
		parent::__construct($action);
	}

	public function execute()
	{
		if (!Application::isGuest()) {
			if (empty($this->action) || in_array(Application::$APP->controller->action, $this->action)) {
				// throw new ForbiddenException();
				Application::$APP->response->redirect('/');
			}
		}
	}
}
