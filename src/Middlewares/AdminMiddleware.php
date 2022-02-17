<?php


namespace Middlewares;

use Model\Role;
use Simfa\Framework\Application;
use Simfa\Framework\Exception\NotFoundException;
use Simfa\Framework\Middleware\BaseMiddleware;

class AdminMiddleware extends BaseMiddleware
{

	public function execute()
	{
		$roles = new Role();
		$roles->getOneBy('user', Application::$APP->user->getId());
		if (!$roles->getId())
			throw new NotFoundException();
	}
}
