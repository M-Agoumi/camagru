<?php


namespace core\Middleware;


interface BaseMiddlewareInterface
{
	public function __construct(array $action = []);
	public function execute();
}
