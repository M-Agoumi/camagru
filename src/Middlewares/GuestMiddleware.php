<?php


namespace Middlewares;

use Simfa\Framework\Application;
use Simfa\Framework\Middleware\BaseMiddleware;

class GuestMiddleware extends BaseMiddleware
{
	public function __construct(array $action = [])
	{
		parent::__construct($action);
	}

	public function execute()
	{
		if (!Application::isGuest()) {
			if (empty($this->action) || in_array(Application::$APP->controller->action, $this->action)) {
				Application::$APP->response->redirect('/');
			}
		}
	}
}
