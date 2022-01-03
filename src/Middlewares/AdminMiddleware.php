<?php


namespace Middlewares;


use Simfa\Application;
use Simfa\Exception\NotFoundException;
use Simfa\Middleware\BaseMiddleware;
use models\Roles;

class AdminMiddleware extends BaseMiddleware
{

	public function execute()
	{
		$roles = new Roles();
		$roles->getOneBy('user', Application::$APP->user->getId());
		if (!$roles->getId())
			throw new NotFoundException();
	}
}
