<?php


namespace Simfa\Framework\Middleware;


interface BaseMiddlewareInterface
{
	public function __construct(array $action = []);
	public function execute();
}
