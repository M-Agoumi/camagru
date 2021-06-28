<?php


namespace Middlewares;


use core\Application;
use core\Exception\NotFoundException;
use core\Middleware\BaseMiddleware;

class DevMiddleware extends BaseMiddleware
{
	public function __construct(array $action = [])
	{
		parent::__construct($action);
	}

	public function execute()
	{
		if (empty($this->action) || in_array(Application::$APP->controller->action, $this->action))
			if (Application::getEnvValue('env') != 'dev' && Application::getEnvValue('env') != 'local')
				throw new NotFoundException();
	}
}
