<?php


namespace Middlewares;


use core\Application;
use core\Exception\NotFoundException;
use core\Middleware\BaseMiddleware;
use models\Roles;

class AdminMiddleware extends BaseMiddleware
{

	public function execute()
	{
		$roles = new Roles();
		$roles = $roles->getOneBy('user', Application::$APP->user->getId());
		if (!$roles)
			throw new NotFoundException();
	}
}
