<?php


namespace Middlewares;


use Simfa\Framework\Application;
use Simfa\Framework\Exception\NotFoundException;
use Simfa\Framework\Middleware\BaseMiddleware;

class DevMiddleware extends BaseMiddleware
{
	public function __construct(array $action = [])
	{
		parent::__construct($action);
	}

	public function execute()
	{
		if (empty($this->action) || in_array(Application::$APP->controller->action, $this->action))
			if (Application::getEnvValue('ENV') != 'dev' && Application::getEnvValue('ENV') != 'local')
				throw new NotFoundException();
	}
}
