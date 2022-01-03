<?php


namespace Simfa\Framework\Middleware;


abstract class BaseMiddleware implements BaseMiddlewareInterface
{
	public array $action = [];

	public function __construct(array $action = [])
	{
		$this->action = $action;
	}

	abstract public function execute();

}
